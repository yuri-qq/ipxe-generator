<?php
    namespace Yurifag\IPXE;
    class Config {
        private $netboot_base_url;
        private $background_img_url;
        private $wimboot_location;
        private $screen_width = 800;
        private $screen_height = 600;

        private function set_netboot_base_url(string $url) {
            $this->netboot_base_url = $url;
        }

        private function get_netboot_base_url(): string {
            return $this->netboot_base_url;
        }

        private function set_background_img_url(string $url) {
            $this->background_img_url = $url;
        }

        private function get_background_img_url(): string {
            return $this->background_img_url;
        }

        private function set_screen_width(int $x) {
            $this->screen_width = $x;
        }

        private function get_screen_width(): int {
            return $this->screen_width;
        }

        private function set_screen_height(int $y) {
            $this->screen_height = $y;
        }

        private function get_screen_height(): int {
            return $this->screen_height;
        }

        private function set_screen_res(int $x, int $y) {
            $this->screen_width = $x;
            $this->screen_height = $y;
        }

        public function __set($property, $value) {
            $func_name = "set_".$property;
            return $this->$func_name($value);
        }

        public function __get($property) {
            $func_name = "get_".$property;
            return $this->$func_name();
        }
    }
?>
