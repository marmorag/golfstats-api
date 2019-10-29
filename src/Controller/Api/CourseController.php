<?php

namespace App\Controller\Api;

use App\Controller\AbstractApiController;
use App\Service\CourseCrawler;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CourseController extends AbstractApiController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @Route("/course-crawl", name="course:crawl", methods={"POST"})
     *
     * @param Request $request
     * @param CourseCrawler $courseCrawler
     * @param SerializerInterface $serializer
     *
     * @return Response
     */
    public function crawl(Request $request, CourseCrawler $courseCrawler, SerializerInterface $serializer): Response
    {
        $url = $request->request->get('url');

        try {
            $course = $courseCrawler->process($url);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return $this->internalFailureResponse($exception->getMessage());
        }

        return $this->successResponse(array('course' => $serializer->serialize($course, 'json')));
    }
}
