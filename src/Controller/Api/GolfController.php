<?php

namespace App\Controller\Api;


use App\Controller\AbstractApiController;
use App\Entity\Golf;
use App\Repository\GolfRepository;
use App\Service\ObjectSerializerGetter;
use \Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GolfController extends AbstractApiController
{

    /**
     * @var \Symfony\Component\Serializer\Serializer
     */
    private $serializer;
    /**
     * @var DocumentManager
     */
    private $documentManager;
    /**
     * @var GolfRepository
     */
    private $golfRepository;
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ObjectSerializerGetter $getter, DocumentManager $documentManager, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->serializer = $getter::getSerializer();
        $this->documentManager = $documentManager;
        $this->golfRepository = $this->documentManager->getRepository(Golf::class);
    }

    /**
     * @Route("/golf", name="golf:create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        /** @var Golf $golf */
        $golf = $this->serializer->deserialize($request->request->get('golf'), Golf::class, 'json');

        if (!$this->golfRepository->exist($golf)){

            $this->documentManager->persist($golf);
            $this->documentManager->flush();

            $this->logger->log('debug', $golf->getId());

            $data = array(
                'data' => $this->serializer->serialize($golf, 'json', ['ignored_attributes' => ['courses', 'contact']]),
            );

            return $this->successResponse($data);
        }
        return $this->invalidRequestResponse('The given golf is already registred.');
    }

    /**
     * @Route("/golf", name="golf:update", methods={"PATCH"})
     *
     * @param Request $request
     *
     * @return Response
     * @throws LockException|MappingException
     */
    public function update(Request $request): Response
    {
        /** @var Golf $course */
        $golf = $this->serializer->deserialize($request->request->get('golf'), Golf::class, 'json');

        /** @var Golf $persistedCourse */
        $persistedGolf = $this->golfRepository->find($golf->getId());

        if (isset($persistedGolf)) {
            $golf->setId($persistedGolf->getId());
            $this->documentManager->persist($golf);
            $this->documentManager->flush();
        } else {
            return $this->unknownRessourceResponse();
        }

        return $this->successResponse();
    }

    /**
     * @Route("/golf/{id}", name="golf:retrieve", methods={"GET"}, requirements={"id"="\S+"})
     * @Route("/golf", name="golf:retrieve_all", methods={"GET"})
     *
     * @param string $id
     *
     * @return Response
     * @throws LockException|MappingException
     */
    public function retrieve(string $id = null): Response
    {

        if (isset($id)){
            $golf = $this->golfRepository->find($id);

            if (!isset($golf)){
                return $this->unknownRessourceResponse();
            }

            $serializedGolf = $this->serializer->serialize($golf, 'json');

            $data = array(
                'data' => $serializedGolf,
                'meta' => array(
                    'type' => 'Golf'
                ),
            );

            return $this->successResponse($data);
        }

        $golfs = $this->golfRepository->findAll();

        $serializedGolfs = $this->serializer->serialize($golfs, 'json', ['ignored_attributes' => ['courses', 'contact']]);

        $data = array(
            'data' => $serializedGolfs,
            'meta' => array(
                'count' => count($golfs),
                'type' => 'Collection:Golf',
            ),
        );

        return $this->successResponse($data);
    }

    /**
     * @Route("/golf/{id}", name="golf:delete", methods={"DELETE"}, requirements={"id"="\S+"})
     *
     * @param string $id
     *
     * @return Response
     * @throws LockException|MappingException
     */
    public function delete(string $id): Response
    {
        $course = $this->golfRepository->find($id);

        if (!isset($course)){
            return $this->unknownRessourceResponse('The provided golf id was not found.');
        }

        $this->documentManager->remove($course);
        $this->documentManager->flush();

        return $this->successResponse('Successfully removed golf.');
    }
}