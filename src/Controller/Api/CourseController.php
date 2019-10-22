<?php

namespace App\Controller\Api;


use App\Controller\AbstractApiController;
use App\Entity\Course;
use App\Service\CourseCrawler;
use App\Service\ObjectSerializerGetter;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractApiController
{

    private $serializer;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var DocumentManager
     */
    private $dm;
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $courseRepository;

    public function __construct(LoggerInterface $logger, DocumentManager $dm)
    {
        $this->serializer = ObjectSerializerGetter::getSerializer();
        $this->logger = $logger;
        $this->dm = $dm;
        $this->courseRepository = $this->dm->getRepository(Course::class);
    }

    /**
     * @Route("/course-crawl", name="course:crawl", methods={"POST"})
     *
     * @param Request $request
     * @param CourseCrawler $courseCrawler
     *
     * @return Response
     */
    public function crawl(Request $request, CourseCrawler $courseCrawler): Response
    {
        $url = $request->request->get('url');

        try {
            $course = $courseCrawler->process($url);
        } catch (\Exception $exception) {
            $this->logger->log('error', $exception);
            return $this->internalFailureResponse($exception->getMessage());
        }

        return $this->successResponse(array('course' => $this->serializer->serialize($course, 'json')));
    }

    /**
     * @Route("/course", name="course:create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        /** @var Course $course */
        $course = $this->serializer->deserialize($request->request->get('course'), Course::class, 'json');

        if ($course->getId() === ''){
            $this->dm->persist($course);
            $this->dm->flush();

            $this->logger->log('debug', $course->getId());
        }

        return $this->successResponse();
    }

    /**
     * @Route("/course", name="course:update", methods={"PATCH"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function update(Request $request): Response
    {
        /** @var Course $course */
        $course = $this->serializer->deserialize($request->request->get('course'), Course::class, 'json');

        /** @var Course $persistedCourse */
        $persistedCourse = $this->courseRepository->find($course->getId());

        if (isset($persistedCourse)) {
            $course->setId($persistedCourse->getId());
            $this->dm->persist($course);
            $this->dm->flush();
        } else {
            return $this->unknownRessourceResponse();
        }

        return $this->successResponse();
    }

    /**
     * @Route("/course/{id}", name="course:retrieve", methods={"GET"}, requirements={"id"="\S+"})
     *
     * @param string $id
     */
    public function retrieve(string $id): void
    {

    }
}