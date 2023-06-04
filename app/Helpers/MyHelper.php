<?php

function tpid_response_success($result = null, $message = null)
{
    return response()->json([
        'status' => "ok",
        'message' => $message,
        'books' => $result
    ]);
}

function tpid_response_error($result = null, $message = null, $code = 400)
{
    if (!(app()->environment('local')) && !is_int($code)) {
        $message = 'Internal Server Errror';
    }

    return response()->json([
        'status' => false,
        'message' => $message,
        'result' => $result
    ],  is_int($code) && $code != 0 ? $code : 500);
}