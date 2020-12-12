<?php
    namespace Yurifag\IPXE;
    class IPXE extends Config {

        public function __construct(array $config = []) {
            header("Content-Type: text/plain");

            if($config["screen_width"])       $this->screen_width       = $config["screen_width"];
            if($config["screen_height"])      $this->screen_height      = $config["screen_height"];
            if($config["background_img_url"]) $this->background_img_url = $config["background_img_url"];
            if($config["netboot_base_url"])   $this->netboot_base_url   = $config["netboot_base_url"];
        }

        public function create_element(string $type, string $text, $items = []) {
            if(!is_array($items)) $items = [$items];

            if($type === "menu") {
                return new Menu($text, $items);
            }
            else if($type === "menuitem") {
                return new MenuItem($text, $items);
            }
            else if($type === "command") {
                return new Command($text, $items);
            }
        }

        public function get_script(Menu $menu): string {
            $script = "#!ipxe\n";
            //$script .= "console --x ".$this->screen_width." --y ".$this->screen_height;
            //if($this->background_img_url) $script .= " --picture ".$this->background_img_url;
            //$script .= "\n";

            $script .= $menu->get_script($this->screen_width);

            return $script;
        }

        protected function build_command(string $filepath): MenuItem {
            $commands = [];
            $pathinfo = pathinfo($filepath);
            $dir = $pathinfo["dirname"].DIRECTORY_SEPARATOR;

            if(isset($pathinfo["extension"]) && $pathinfo["extension"] === "wim") {
                if(file_exists($dir."BCD") && file_exists($dir."boot.sdi")) {
                    $commands[] = $this->create_element("command", "kernel", ["wimboot"]);
                    $commands[] = $this->create_element("command", "initrd", [$this->netboot_base_url.$dir."BCD", "BCD"]);
                    $commands[] = $this->create_element("command", "initrd", [$this->netboot_base_url.$dir."boot.sdi", "boot.sdi"]);
                    $commands[] = $this->create_element("command", "initrd", [$this->netboot_base_url.$filepath, "boot.wim"]);
                }
            }

            if(file_exists($dir."linux")) {
                $kernel = $this->netboot_base_url.$dir."linux";
            }
            else if(file_exists($dir."vmlinuz")) {
                $kernel = $this->netboot_base_url.$dir."vmlinuz";
            }

            if(file_exists($dir."initrd.gz")) {
                $initrd = $this->netboot_base_url.$dir."initrd.gz";
            }
            else if(file_exists($dir."initrd.img")) {
                $initrd = $this->netboot_base_url.$dir."initrd.img";
            }

            $commands[] = $this->create_element("command", "kernel", [$kernel]);
            $commands[] = $this->create_element("command", "initrd", [$initrd]);
            $commands[] = $this->create_element("command", "boot");

            return $this->create_element("menuitem", $filepath, $commands);
        }

        /**
         * Recursively walk directory and create iPXE items and sub-menus.
         * @param  string $path path to scan, relative to the directory where this is executed
         * @return array        iPXE menu items
         */
        public function generate_from_dir(string $path): array {
            $directory_list = array_values(array_diff(scandir($path), [".", ".."]));
            $items = [];

            for($i = 0; $i < sizeof($directory_list); $i++) {
                $filepath = $path.DIRECTORY_SEPARATOR.$directory_list[$i];
                if(is_dir($filepath)) {
                    $items[] = new Menu($directory_list[$i], $this->generate_from_dir($filepath));
                }
                else if(is_dir($directory_list[$i]) || $directory_list[$i] === "linux" || $directory_list[$i] === "vmlinuz" || $directory_list[$i] === "initrd.gz" || $directory_list[$i] === "initrd.img") {
                    $info = pathinfo($directory_list[$i]);
                    $items[] = $this->build_command($filepath);
                    /* if((isset($info["extension"]) && $info["extension"] === "wim") || file_exists($info["dirname"].DIRECTORY_SEPARATOR."initrd.gz")) {

                    } */
                }
            }

            return $items;
        }

    }
?>
