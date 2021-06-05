<?php

class game{

    private $ill = ['░░', '▓▓', "@"];
    private $offset = "\n";
    private $act = ['R', 'R', 'L', 'L', 'L', 'R', 'L', 'L', 'L', 'R', 'R', 'R'];

    private function check_map(){
        if($this->map)
            return true;
        else
            throw new Exception("Error! The map value is not specified.");
    }

    private function rotate($type, $deg, $position){
        if($type == 1){
            $position += $deg;
            if($position >= 360) $position -= 360;
        }
        if($type == 0){
            $position -= $deg;
            if($position < 0) $position += 360;
        }
        return $position;
    }

    private function side($deg){
        if($deg == 0) return [-1, 0];
        if($deg == 90) return [0, 1];
        if($deg == 180) return [1, 0];
        if($deg == 270) return [0, -1];
    }

    private function pattern($move){
        $next = $this->act[$move] == 'L' ? 0 : 1;
        return [$next, count($this->act) > $move + 1 ? $move + 1 : 0];
    }

    public function generate($size){
        for ($i=0; $i < $size; $i++) { 
            for ($j=0; $j < $size; $j++) {
                $this->map[$i][$j] = [0, $this->ill[0]];
            }
        }
    }

    public function next_step(){
        $size = count($this->map);
        $map = $this->map;
        for ($i=0; $i < $size; $i++) { 
            for ($j=0; $j < $size; $j++){
                if(count($this->map[$i][$j]) == 4){
                    if($this->map[$i][$j][2] == $this->ill[2] && count($this->map[$i][$j]) == 4){
                        $move = $this->pattern($this->map[$i][$j][0]);
                        $map[$i][$j] = [$move[1], $this->map[$i][$j][0] == 1 ? $this->ill[0] : $this->ill[1]];
                        $pos = $this->rotate($move[0], 90, $this->map[$i][$j][3]);
                        $side = $this->side($pos);
                        $map[$i + $side[0]][$j + $side[1]] = [$map[$i + $side[0]][$j + $side[1]][0], $map[$i + $side[0]][$j + $side[1]][1], $this->ill[2], $pos];$map[$i + $side[0]][$j + $side[1]] = [$map[$i + $side[0]][$j + $side[1]][0], $map[$i + $side[0]][$j + $side[1]][1], $this->ill[2], $pos];
                    }
                }
            }
        }
        $this->map = $map;
    }

    public function put_ant($position, $type = 2){
        $this->map[$position[0]][$position[1]] = [0, $this->ill[0], $this->ill[$type], 0];
    }

    public function compile(){
        $this->check_map();
        $size = count($this->map);
        $string = '';
        for ($i=0; $i < $size; $i++) { 
            for ($j=0; $j < $size; $j++) 
            $string .= count($this->map[$i][$j]) == 4 ? $this->map[$i][$j][2] : $this->map[$i][$j][1];
            $string .= $this->offset;
        }
        return $string;
    }

}