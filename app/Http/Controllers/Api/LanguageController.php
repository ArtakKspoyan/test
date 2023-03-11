<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LanguageRequest;
use App\Models\Language;
use App\Models\Translation;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Label;

class LanguageController extends Controller
{


    /**
     * @param LanguageRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LanguageRequest $request)
    {
        $lang = Language::query()->where( 'translation_key', '=', $request->translation_key)->first();

        if(! $lang) {
            $newLang = Language::query()->create(['translation_key' => $request->translation_key]);
            Translation::create([
                'language' => $request->language,
                'text' => $request->translated_text,
                'lg_id' =>  $newLang->id,
            ]);

            return response()->json('success', 200);
        }

        Translation::updateOrCreate([
            'lg_id' => $lang->id,
            'language' => $request->language,
        ],[
            'text' => $request->translated_text,
        ]);

        return response()->json('success', 200);


    }
}
