<?php


namespace App\Api;


use App\Authentication\Repository\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserInfoApiController extends BaseApiController
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserInfoApiController constructor.
     * @param Request $request
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(Request $request, UserRepositoryInterface $userRepository)
    {
        parent::__construct($request);
        $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    public function getInfoAction(): Response
    {
        $userId = $this->request->query->getDigits('id', null);
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            $data = ['error' => 'no such user'];
            $this->response->setStatusCode(404);
        } else {
            $data = $user->toArray();
        }
        $this->render($data);
        return $this->response;
    }
}
