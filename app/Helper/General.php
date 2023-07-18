<?php

/**
 * upload file
 *
 *
 * @param $request
 * @param $name
 * @param string $destination
 * @return string
 */
function uploadFile($request, $name, $destination = '')
{
    $image = $request->file($name);

    $name = time().'.'.$image->getClientOriginalExtension();

    if($destination == '') {
        $destination = public_path('/uploads');
    }

    $image->move($destination, $name);

    return $name;
}




/**
 * check if user has permission
 *
 *
 * @param $permission
 * @return bool
 */
function user_can($permission)
{
    //return \Auth::user()->is_admin == 1 || \Auth::user()->can($permission);
}




/**
 * get Users
 *
 *
 * @return mixed
 */
function getUsers()
{
    return \App\User::where('is_admin', 0)->where('enabled', 1)->get();
}