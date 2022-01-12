<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {

        $content = Content::find($id);
        if (is_null($article)) 
            return redirect()->back()->withErrors(['content' => 'Content not found, id:'.$id]);

        $this->authorize('update', $content);

        $validator = Validator::make($request -> all(),
        [
            'like' => 'nullable|boolean',
        ]);
        
        return Response()->json([
            'status' => 'OK',
            'msg' => 'Successfully updated content: '.$id,
            'likes' => $content->likes,
            'dislikes' => $content->dislikes
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Content $content)
    {
        //
    }
}
