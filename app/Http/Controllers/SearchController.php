<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function igFollowers(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('ig_followers_'.$id)){
            $people = collect(Cache::get('ig_followers_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = $filtered->map(function ($value, $key) {
                return ['id' => $key, 'text' => '@'.$value[0]];
            });

            return response()->json($results->all());
        }else{
            return response()->json([]);
        }
    }

    public function igFollowing(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('ig_following_'.$id)){
            $people = collect(Cache::get('ig_following_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }

    public function igNonFollowers(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('ig_non_followers_'.$id)){
            $people = collect(Cache::get('ig_non_followers_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }

    public function igNonFollowing(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('ig_non_following_'.$id)){
            $people = collect(Cache::get('ig_non_following_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }
    
    public function twFollowers(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('tw_followers_'.$id)){
            $people = collect(Cache::get('tw_followers_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }

    public function twFollowing(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('tw_following_'.$id)){
            $people = collect(Cache::get('tw_following_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }

    public function twNonFollowers(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('tw_non_followers_'.$id)){
            $people = collect(Cache::get('tw_non_followers_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }

    public function twNonFollowing(Request $request){
        $keyword = $request->get('q');
        $id = $request->get('id');

        if(Cache::has('tw_non_following_'.$id)){
            $people = collect(Cache::get('tw_non_following_'.$id));
            $filtered = $people->filter(function ($value, $key) use ($keyword) {
                return preg_match('/.*'.$keyword.'.*$/i', $value[0]);
            });

            $results = [];
            foreach($filtered->all() as $key => $data){
                $results[] = ['id' => (string) $key, 'text' => '@'.$data[0]];
            }

            return response()->json($results);
        }else{
            return response()->json([]);
        }
    }
}
