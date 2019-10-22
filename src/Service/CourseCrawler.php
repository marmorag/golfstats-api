<?php
namespace App\Service;

use App\Entity\Landmark;
use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;

use App\Entity\Course;
use App\Entity\Hole;
use InvalidArgumentException;
use Symfony\Component\Panther\DomCrawler\Field\ChoiceFormField;

class CourseCrawler
{

    private $client;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = Client::createChromeClient(__DIR__.'/../../vendor/symfony/panther/chromedriver-bin/chromedriver_linux64');
        $this->logger = $logger;
    }

    /**
     * @param $url
     * @return Course
     *
     * @throws TimeOutException|InvalidArgumentException
     */
    public function process($url): Course
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
     * @throws TimeOutException
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
        $tees = array();

        // Extract all tee box available
        foreach ($crawler->filter('#landmarkList')->children() as $tee){
            $tees[$tee->getText()] = $tee->getAttribute('value');
        }

        // For each tee box, extract holes data, slope and sss
        foreach ($tees as $tee => $domValue) {
            $holes = array();
            // Trigger js to update the table values
            // jQuery is included in the page so it helps
            $this->client->executeScript("$('#landmarkList').val($domValue).trigger('change');");
            $localCrawler = $this->client->getCrawler();

            $landmark = new Landmark();

            // Extract landmark metadata
            $landmark->setName($tee);
            $landmark->setSlopeMen($localCrawler->filter('#slope-mess')->getText());
            $landmark->setSlopeLady($localCrawler->filter('#slope-dame')->getText());
            $landmark->setSssMen($localCrawler->filter('#sss-mess')->getText());
            $landmark->setSssLady($localCrawler->filter('#sss-dame')->getText());

            // Extract holes metadata
            for ($i = 1; $i <= 18; $i++) {
                $hole = new Hole();

                $hole->setNumber($i);
                $hole->setPar((int)$localCrawler->filter("#par-$i")->getText());
                $hole->setHandicap((int)$localCrawler->filter("#hcp-$i")->getText());
                $hole->setLength((int)$localCrawler->filter("#dist-$i")->getText());

                $holes[] = $hole;
            }

            $landmark->setHoles($holes);
            $course->addLandmark($landmark);
        }
    }
}