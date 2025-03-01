<?php


namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;

use Exception;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use DB;
use Session;


class Handler extends ExceptionHandler

{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */

    protected $dontReport = [

        //

    ];


    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */

    protected $dontFlash = [

        'password',

        'password_confirmation',

    ];


    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */

    public function report(Exception $exception)

    {

        parent::report($exception);

    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Exception $exception)

    {

        return parent::render($request, $exception);

    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        $guard = array_get($exception->guards(), 0);

        if ($guard == 'admin') {
            $admin = DB::table('system_admin')->where('id_admin', Session::get('id_admin'))->first();
            if ($admin->main_id_at == 0) {
                return redirect()->guest(route('admin.dashboard'));
            } else if ($admin->main_id_at > 0) {
                return redirect('backoffice/name-blog');

            } else {
                return redirect()->guest(route('admin.login'));
            }
        } else {
            return redirect()->guest(route('admin.login'));
        }


//        $guard = array_get($exception->guards(), 0);
//
//        switch ($guard) {
//            case 'admin':
//                $login = 'admin.dashboard';
//                break;
//
//            default:
//                $login = 'admin.login';
//                break;
//        }
//        return redirect()->guest(route($login));
    }

}

