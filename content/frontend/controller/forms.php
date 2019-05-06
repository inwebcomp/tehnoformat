<?php

use Hex\Helpers\Base64File;

class controller_forms
{
    public function contact()
    {
        $content = [];

        if (Application::wantsJson()) {
            $entityBody = @json_decode(file_get_contents('php://input'), true);

            $params = [
                'name' => trim($entityBody['name']),
                'phone' => trim($entityBody['phone']),
                'email' => trim($entityBody['email']),
                'message' => trim($entityBody['message']),
                'file' => $entityBody['file'],
                'fileName' => $entityBody['fileName']
            ];

            $data = Forms::Validate($params, "contact");

            $dataArr = $data->GetInfo();

            $file = new Base64File($params['file']);

            if (! $file->checkSize(10)) {
                $content = $dataArr;
                $content["error"] = 1;
                $content["message"] = lang('Размер файла не должен превышать 20 Мб.');
                return $content;
            }

            if ($file->ext != '' and ! $file->checkExtension(['jpg', 'jpeg', 'png', 'zip'])) {
                $content = $dataArr;
                $content["error"] = 1;
                $content["message"] = lang('Неверный формат файла');
                return $content;
            }

            $file->save(Model::$conf->tmpPath . '/uploaded/' . $params['fileName']);

            if ($data->err->Val() !== 1) {
                list($subject, $text) = Mailtemplates::CreateLetter("contact", $dataArr);

                if (
                    $subject and
                    $text and
                    Mail::Send(
                        Model::$conf->email,
                        $subject,
                        $text,
                        ($data->email != '' ? $data->email : $data->name),
                        null,
                        [$file->path]
                    )
                ) {
                    $content["message"] = lang('Спасибо! Мы с вами скоро свяжемся!');
                } else {
                    $content = $dataArr;
                    $content["message"] = lang('Произошла ошибка');
                    $content["error"] = 1;
                }
            } else {
                $content = $dataArr;
                $content["error"] = 1;
                $content["message"] = lang('Произошла ошибка');
            }

            if ($file->isSaved())
                $file->remove();
        }

        return $content;
    }
}