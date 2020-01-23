<?php
namespace App\Service;

use App\Entity\Course;
use App\Entity\Hole;
use App\Entity\Landmark;
use Doctrine\Common\Collections\ArrayCollection;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class CourseCrawler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private Client $client;

    public function __construct()
    {
        $this->client = Client::createChromeClient(__DIR__.'/../../vendor/symfony/panther/chromedriver-bin/chromedriver_linux64');
    }

    /**
     * @param string $url
     * @return Course
     *
     * @throws TimeOutException|InvalidArgumentException
     */
    public function process(string $url): Course
    {
        $crawler = $this->client->request('GET', $url);
        $course = new Course();

        try {
            $this->extractName($crawler, $course);
            $this->extractHoles($crawler, $course);
        } catch (NoSuchElementException $e) {
            throw new InvalidArgumentException('The provided link do not refer to a crawlable page');
        }

        $this->client->close();
        return $course;
    }

    /**
     * Extract course name from given crawler
     *
     * @param Crawler $crawler
     * @param Course $course
     *
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    private function extractName(Crawler $crawler, Course $course): void
    {
        $this->client->waitFor('div.elbloc.mb30 div.head');
        $name = $crawler->filter('div.elbloc.mb30 div.head')->text();

        // Split GOLF CLUB NAME - COURSE NAME
        $composition = explode('-', $name);
        $course->setName(ucwords(strtolower(trim($composition[1]))));
    }

    /**
     * Extract Holes data from given crawler and set Hole array
     *
     * @param Crawler $crawler
     * @param Course $course
     */
    private function extractHoles(Crawler $crawler, Course $course): void
    {
        $tees = new ArrayCollection();

        // Extract all tee box available
        foreach ($crawler->filter('#landmarkList')->children() as $tee) {
            $tees->set($tee->getText(), $tee->getAttribute('value'));
        }

        // For each tee box, extract holes data, slope and sss
        foreach ($tees as $tee => $domValue) {
            /** @var string $tee */

            $holes = new ArrayCollection();
            // Trigger js to update the table values
            // jQuery is included in the page so it helps
            $this->client->executeScript("$('#landmarkList').val($domValue).trigger('change');");
            $localCrawler = $this->client->getCrawler();

            $landmark = new Landmark();

            // Extract landmark metadata
            $landmark->setName($tee);
            $landmark->setSlopeMen((float)$localCrawler->filter('#slope-mess')->getText());
            $landmark->setSlopeLady((float)$localCrawler->filter('#slope-dame')->getText());
            $landmark->setSssMen((float)$localCrawler->filter('#sss-mess')->getText());
            $landmark->setSssLady((float)$localCrawler->filter('#sss-dame')->getText());

            // Extract holes metadata
            for ($i = 1; $i <= 18; $i++) {
                $hole = new Hole();

                $hole->setNumber($i);
                $hole->setPar((int)$localCrawler->filter("#par-$i")->getText());
                $hole->setHandicap((int)$localCrawler->filter("#hcp-$i")->getText());
                $hole->setLength((int)$localCrawler->filter("#dist-$i")->getText());

                $holes->add($hole);
            }

            $landmark->setHoles($holes);
            $course->addLandmark($landmark);
        }
    }
}
