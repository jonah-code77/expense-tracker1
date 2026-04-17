<?php
namespace App\Core;

//use finfo;
class Validator{
    //required validation
    public static function required($value, $param = null){
        if (trim((string)$value) === "") return $param ?? " is needed";
        return null;
    }

    //email validation
    public static function email($value){
        if ($value === "") return null;

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) return "invalid Email format";

        return null;
    }

    //gender validation
    public static function gender($value){
        if ($value === "") {
            return null;
        }
        if(!\in_array($value, ['male', 'female'])){
            return "invalid Gender";
        }
        return null;
    }

      //type notification validation
    public static function type($value){
        if ($value === "") {
            return null;
        }
        if(!\in_array($value, ['assignment', 'announcement', 'system'])){
            return "invalid type";
        }
        return null;
    }

    //image validation
    public static function image($file, $maxsize = 3){
        if (!isset($file)) return "profile picture is required";

        if($file['error'] !== UPLOAD_ERR_OK) return "file upload failed";

        if(!@getimagesize($file['tmp_name']))return "invalid image file";

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        $allowedMine = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ];

        if (!\in_array($mime , $allowedMine)) {
            return "invalid image file";
        }

        $allowed_ext = [
            'jpg', 
            'jpeg', 
            'png', 
            'gif'
        ];
        $ext = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        if (!\in_array($ext, $allowed_ext)) {
            return "invalid image type only JPG,JPEG,PNG AND GIF is allowed";
        }

        if($file['size'] > ($maxsize * 1024 * 1024)){
            return "image size exceed {$maxsize}mb";
        }

        return null;
    }

    //file validation
    public static function uploadFile($file, $maxsize = 10){
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return "file upload is required";
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return match ($file['error']) {
                UPLOAD_ERR_INI_SIZE,
                UPLOAD_ERR_FORM_SIZE => "file too large",
                UPLOAD_ERR_PARTIAL => "file upload was interrupted",
                default => "file upload failed"
            };
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        $allowedMime = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ];

        if (!\in_array($mime, $allowedMime)) return "invalid file content";


        $allowed_ext = ['pdf', 'doc', 'docx', 'txt'];

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!\in_array($ext, $allowed_ext)) return "invalid file type — only pdf, doc, docx and txt allowed";


        if ($file['size'] > ($maxsize * 1024 * 1024)) return "file exceeds {$maxsize}MB";


        return null;
    }

    //Make Engine
    public static function make( $data,  $rules){
        $errors = [];
        foreach ($rules as $field => $ruleString) {
            //$rules = ['email' => 'required|email'];
            //$rules = ['name' => 'required:name is required'];

            $rulesArr = explode('|', $ruleString);
            //$rulesArr = ['required', 'email']
            //rulesArr = ['required:name is required']
            
            foreach ($rulesArr as $rule) {
                
                $value = $data[$field] ?? "";
                //$value = $data['email]
                //$value = $data['name']

                // Handle rules with parameters like image:3
                if (str_contains($rule, ':')) {
                    [$ruleName, $param] = explode(':', $rule, 2);

                    //['required','name is required] = (':', 'required:name is required')
                } else {
                    $ruleName = $rule;
                    $param = null;
                    //['required'] = required
                }
                // Match rule to method
                if (!method_exists(__CLASS__, $ruleName)) continue;
                //!method_exist(App\\Core\\Validator, required) continue

                // File rules read from $_FILES, everything else from $data
                //assuming we have files to upload..they follow this rule,,lets assume our rule is img
                $isFileRule = \in_array($ruleName, ['image', 'uploadFile']);
                //$isfilerule =in_array(img,['image, 'uploadAssignment])

                if ($isFileRule) {
                    $error = self::$ruleName($_FILES[$field] ?? null, $param);
                    //error = validator::img($_FILES['img'] ?? null, 3) 
                } else {
                    $value = $data[$field] ?? "";
                    //value = $data['img'] ?? null
                    $error = $param !== null
                    //error = $param
                        ? self::$ruleName($value, $param)
                        : self::$ruleName($value);
                }

                if ($error) {
                    $errors[$field] = $error;
                    break; 
                }
            }
        }
        return $errors;
    }

}