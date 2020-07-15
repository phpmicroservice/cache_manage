<?php

namespace App\Helper\Cache\user;

use Illuminate\Support\Facades\Log;

/**
 * Description of Exp
 * 常用快递
 * @author dongasai
 */
class Cainixihuan
        extends \App\Helper\AbstractCache
{

    protected $ttl = 120;

    /**
     * 清空
     */
    public function flush()
    {
        ;
    }

    /**
     * 涉及标签
     * @return array
     */
    public function tags(): array
    {
        return [
        ];
    }

    public function handle()
    {
        $user_id = $this->param_arr['user_id'];
        $type    = $this->param_arr['type'];
        if ($type) {
            $ids = \App\Models\UserBrowse::where('user_id', $user_id)
                            ->where('type', $type)
                            ->limit(500)
                            ->orderBy('id','desc')
                            ->pluck('document_id')->toArray();
        } else {
            $ids = \App\Models\UserBrowse::where('user_id', $user_id)
                            ->limit(500)
                            ->orderBy('id','desc')
                            ->pluck('document_id')->toArray();
        }

        if (!$ids) {
            # 没有浏览记录
            return [-1];
        }
        $colist = \App\Models\DocumentIndex::whereIn('id', $ids)
                        ->orderBy('type_id')
                        ->groupBy('type_id')
                        ->select(\Illuminate\Support\Facades\DB::raw('COUNT(*) AS value'), 'type_id')
//                ->get();
                        ->pluck('value', 'type_id')->toArray();
        Log::info('猜你喜欢数据:', [
            'user_id' => $user_id,
            'type'    => $type,
            'colist'  => $colist
        ]);
        $count = array_sum($colist);
        $tuijianzongshu = 500;
        $tuijian=[];
        foreach ($colist as $type_id=>$count55){
            $bili = bcdiv($count55, $count,2);
            // 读取总数
            $duquzongshu = bcmul($tuijianzongshu, $bili,0);
            if($type){
                 $tuijian+=\App\Models\DocumentIndex::where('type_id',$type_id)
                    ->where('type',$type)
                    ->where('review_status',2)
                    ->inRandomOrder()
                    ->take($duquzongshu)
                    ->pluck('id')->toArray();    
            }else{
                 $tuijian+=\App\Models\DocumentIndex::where('type_id',$type_id)
                    ->where('review_status',2)
                    ->inRandomOrder()
                    ->take($duquzongshu)
                    ->pluck('id')->toArray();    
            }
               
        }
        return $tuijian;
    }

}
