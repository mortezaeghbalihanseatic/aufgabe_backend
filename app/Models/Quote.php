<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *      schema="Quote",
 *      @OA\Property(
 *          property="quote",
 *          type="string",
 *          description="The quote text."
 *      ),
 *      @OA\Property(
 *          property="character",
 *          type="string",
 *          description="The name of the character who said the quote."
 *      ),
 *      @OA\Property(
 *          property="image_url",
 *          type="string",
 *          description="The URL of an image related to the quote (if available)."
 *      ),
 * )
 */

class Quote extends Model
{
    protected $fillable = ['quote', 'character', 'image_url'];
}
