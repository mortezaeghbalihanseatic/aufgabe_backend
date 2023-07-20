<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TestDbController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/dbStat",
 *     @OA\Response(response="200", description="An example endpoint")
 * )
 */
    public function index()
    {
        $databaseStatus = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_INFO); 
        $databaseStatus = [
          'DatabaseStatus' => $databaseStatus,
        ];
        return response()->json($databaseStatus);
    }
}
