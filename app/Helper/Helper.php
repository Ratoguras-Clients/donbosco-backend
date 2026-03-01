<?php

namespace App\Helper;

use App\Models\ModelHasModules;
use App\Models\Module;
use App\Models\UserHasOtp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class Helper
{

    public function sms($number, $otp, $data = [])
    {
        // $response = Http::asForm()->post(config('aakash-sms.base_url'), [
        //     'auth_token' => config('aakash-sms.token'),
        //     'to'         => $number,
        //     'text'       => $otp,
        // ]);

        $userotp = new UserHasOtp();
        $userotp->phone_number = $number;
        $userotp->generated_at = now();
        if (!empty($data)) {
            $userotp->otp = $data['otp'];
            $userotp->type = $data['type'];
        }
        if ($userotp->save()) {
            return response()->json(['message' => 'SMS sent successfully']);
        }
    }

    public static function currentSubdomain(): ?string
    {
        $host = Request::getHost();
        $parts = explode('.', $host);
        if (count($parts) >= 3) {
            return $parts[0];
        }
        return null;
    }

    public static function currentModuleId(): ?int
    {
        $subdomain = self::currentSubdomain();
        if (!$subdomain) return null;

        return \App\Models\Module::where('sub_domain', $subdomain)->value('id');
    }


    public function storeModalHasModule($data = [])
    {
        $validator = Validator::make($data, [
            'namespace' => 'required|string',
            'model_name' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return [
                "success" => false,
                "message" => $validator->errors()->all()
            ];
        }

        $namespace = $data['namespace'];
        $model_name = $data['model_name'];
        $model_id = $data['model_id'];

        $module = Module::where('sub_domain', $this->currentSubdomain())->first();

        if (!$module) {
            return [
                "success" => false,
                "message" => "Module not found for current subdomain!"
            ];
        }

        $module_id = $module->id;

        $alreadyExists = ModelHasModules::where([
            'namespace' => $namespace,
            'model_name' => $model_name,
            'model_id' => $model_id,
            'module_id' => $module_id,
        ])->exists();

        if ($alreadyExists) {
            return [
                "success" => true,
                "message" => "Already exists, no need to re-insert."
            ];
        }

        $save = new ModelHasModules();
        $save->namespace = $namespace;
        $save->model_name = $model_name;
        $save->model_id = $model_id;
        $save->module_id = $module_id;

        if ($save->save()) {
            return [
                "success" => true,
                "message" => "Saved successfully!"
            ];
        }

        return [
            "success" => false,
            "message" => "Failed to save model-module relation!"
        ];
    }


    public static function getSidebarConfig(): ?array
    {
        $subdomain = self::currentSubdomain();

        $defaultConfigPath = resource_path('json/sidebar.json');
        $moduleConfigPath = $subdomain
            ? base_path("Modules/" . ucfirst($subdomain) . "/Resources/json/sidebar.json")
            : null;

        if ($moduleConfigPath && file_exists($moduleConfigPath)) {
            $json = file_get_contents($moduleConfigPath);
        } elseif (file_exists($defaultConfigPath) && $subdomain === null) {
            $json = file_get_contents($defaultConfigPath);
        } else {
            $json = file_get_contents(resource_path('json/nojson.json'));
        }

        return json_decode($json, true);
    }
}
