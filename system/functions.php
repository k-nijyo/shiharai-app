<?php

function set_command_line_value($only_integer = true) {
    while (true) {
        $args = fgets(STDIN, 10);

        $args = rtrim($args, "\n");

        if (ctype_digit($args)) {
            return $args;
            break;
        }

        if ($only_integer ===  true)  {
            echo "整数を入力して下さい。\n";
        }
    }
}
