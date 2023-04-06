<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ProjectSettings extends Model
{
    use HasFactory;

    protected $guarded = [];

    /*function add_settings($group,$key,$value,$is_serialized="0"){
        global $conn;
        $query = mysqli_query($conn, "SELECT var_id FROM ".SETTINGS_TABLE." WHERE var_group='$group' AND var_key='$key'");
        if(mysqli_num_rows($query)>0) mysqli_query($conn, "UPDATE ".SETTINGS_TABLE." SET var_value='$value' WHERE var_group='$group' AND var_key='$key'");
        else mysqli_query($conn, "INSERT INTO ".SETTINGS_TABLE." SET var_group='$group', var_key='$key', var_value='$value', var_is_serialized='$is_serialized'");
    }*/
    public function setSetting($table, $key, $value)
    {
        $data = DB::table($table)->first();
        if(!$data) DB::table($table)->insert(['id'=>1]);
        if(isset($key)){            
            if($value=='Enabled') $value = true;
            else if($value=='Disabled') $value = false;
            DB::table($table)->update([$key=> $value]);
        }

    }

    public function add_setting($group, $key, $value, $serializeable = "0")
    {
        if ($serializeable == '1') {
            $sval = serialize($value);
            $pass_value = base64_encode($sval);
        } else $pass_value = $value;

        DB::table('project_settings')->upsert(
            [
                [
                    'setting_group' => $group,
                    'setting_key' => $key,
                    'setting_value' => $pass_value,
                    'setting_is_serialized' => $serializeable
                ]
            ],
            ['setting_group', 'setting_key'],
            ['setting_value']
        );
    }

    public function add_setting_array($data = array())
    {
        if (!empty($data)) {
            foreach ($data as $dt) {
                if (empty($dt['serializeable'])) $dt['serializeable'] = 0;

                $this->add_setting($dt['group'], $dt['setting_key'], $dt['value'], $dt['serializeable']);
            }
        }
    }

    public function get_settings_by_group($group)
    {
        $pdetails = DB::table('project_settings')->where(['setting_group' => $group])->get();
        $data = array();
        if (!empty($pdetails)) {
            foreach ($pdetails as $pd) {
                if ($pd->setting_is_serialized == '1') {
                    $sval = base64_decode($pd->setting_value);
                    $arr = unserialize($sval);
                    $data[$pd->setting_key] = $arr;
                } else $data[$pd->setting_key] = $pd->setting_value;
            }
        }

        return $data;
    }

    public function get_setting_by_group_key($group, $key)
    {
        $pd = DB::table('project_settings')->where(['setting_group' => $group, 'setting_key' => $key])->first();
        $data = 0;
        if (isset($pd)) {
            if ($pd->setting_is_serialized == '1') {
                $sval = base64_decode($pd->setting_value);
                $arr = unserialize($sval);
                $data = $arr;
            } else $data = $pd->setting_value;
        }

        return $data;
    }
}
