<?php
    $dest_foldeu = "corrupted";
    $number_to_corrupt = 100;
    
    if (!is_dir($dest_foldeu)) mkdir(__DIR__."/" . $dest_foldeu, 0777);

    if ($argc > 3){
        $input_file = $argv[1];
        $header_size = (int) $argv[2];
        $byte_substraction = (isset($argv[3])) ? ((bool) $argv[3]) : 0;
        $allow_all = (isset($argv[4])) ? ((bool) $argv[4]) : false;
        $create_backup = (isset($argv[5])) ? ((bool) $argv[5]) : true;

        if (is_file($input_file)){
            $file_mime = mime_content_type($argv[1]);
            if (!$allow_all){
                switch($file_mime){
                    case "image/jpeg":
                    case "image/png":
                    case "image/bmp":
                        echo "File is a image\n";
                        break;
                    default:
                        die("Invalid file input\n");
                }
            }
            
            $file_content = file_get_contents($input_file);
            $real_content = file_get_contents($input_file);
            
            if ($create_backup) @file_put_contents($input_file . "_backup" . rand(0, 999) . "." . explode(".", $input_file)[1], $file_content);
            $file_size = strlen($file_content);
            $file_headers = substr($file_content, 0, -($header_size));
            $file_content = substr($file_content, $header_size);
            $headerless_size = strlen($file_content);
            exec("xxd " . $input_file, $rep);
            print_r($rep);

            //$new_shit = substr($real_content, 0, -1); //here i will just remove one byte of the dog
            //file_put_contents($input_file, $new_shit);

            $b_h = [];
            for ($i = 0; $i < 0; $i++) {  //lets say we are randomly remplacing 10 bytes
                
                $selected_byte = rand($header_size, $headerless_size);
                array_push($b_h, $selected_byte);
                $real_content[$selected_byte] = "4";//44 //and here im just remplacing a random offset to :flushed:
            }
            
            //oh its because its 5 1 like its 2 byte in 1
            file_put_contents($input_file, $real_content); //here i will join 100% untouchned headers to the corrupted content
            echo "Edited offsets: \n";
            print_r($b_h);
        }else die("File doesn't exist");
    }else{
        die("usage: " . explode("/", __FILE__)[count(explode("/", __FILE__)) - 1] . " filename header_size byte_substraction ?allow_all ?create_backup\n\nfilename:\tstring\tdog.jpg\nheader_size:\tint\t2000\nbyte_substraction:\tint\t0\n(optional)  allow_all:\ttrue/false\tfalse\n(optional) create_backup:\ttrue/false\ttrue\n");
    }