<?php


namespace App\Api;


use App\Authentication\Repository\UserInfoRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserInfoApiController extends BaseApiController
{
    /**
     * @var UserInfoRepositoryInterface
     */
    private $userInfoRepository;

    /**
     * UserInfoApiController constructor.
     * @param Request $request
     * @param UserInfoRepositoryInterface $userInfoRepository
     */
    public function __construct(Request $request, UserInfoRepositoryInterface $userInfoRepository)
    {
        parent::__construct($request);
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * @return Response
     */
    public function getInfoAction(): Response
    {
        $userId = $this->request->query->getDigits('id', null);
        if ($userId) {
            $data = $this->userInfoRepository->getInfo($userId);
        } else {
            $data = ['error' => 'no such user'];
        }
        $this->render($data);
        return $this->response;
    }

}