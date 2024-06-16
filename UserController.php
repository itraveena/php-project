<?php

namespace App\Http\Controllers;

use App\Libraries\ApiResponse;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Interfaces\User\UserServiceInterface;
use Exception;
class UserController extends Controller
{
   
    public function __construct(private UserServiceInterface $userService)
    {
        
    }

    public function getUser(Request $request)
    {
        try {
            $userId = $request->get('user_id');

            return ApiResponse::successMessage($this->userService->getByID($userId));
        } catch (\Throwable $exception) {
            return ApiResponse::failedMessage($exception->getMessage());
        } catch (Exception $exception) {
            CommonHelper::exceptionError($exception);
            return ApiResponse::failedMessage(SERVER_ERROR);
        }
    }
}
