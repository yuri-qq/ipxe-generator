<?php
    namespace Yurifag\IPXE;
    class Menu extends MenuItem {
        private $items;

        /**
         * Menu containing MenuItem objects
         * @param string $text   Menu title
         * @param array  $items  List of MenuItem and/or Menu objects
         */
        public function __construct(string $text, array $items) {
            parent::__construct($text);
            $this->items = $items;

            for($i = 0; $i < sizeof($this->items); $i++) {
                if(get_class($this->items[$i]) === "Yurifag\IPXE\Menu") {
                    array_unshift($this->items[$i]->items, new MenuItem("back", [
                        new Command("goto", [$this->id])
                    ]));
                }
            }
        }

        /**
         * creates a menu title from the menu's text
         * @return string iPXE script
         */
        private function get_title(int $screen_char_width): string {
            $title = $this->text;
            $title_length = strlen($title);
            $padding_left = intval(($screen_char_width - $title_length) / 2);
            $padding_right = $screen_char_width - $padding_left - $title_length;
            $title = str_repeat("-", $padding_left).$title.str_repeat("-", $padding_right);
            return "item --gap -- ".$title."\n";
        }

        /**
         * print iPXE menu recursively with all sub-menus
         * @return string iPXE script
         */
        public function get_script(int $screen_char_width): string {
            $menu_string = ":".$this->id."\n";
            $menu_string .= "menu ".$this->text."\n";
            $menu_string .= $this->get_title($screen_char_width);

            for($i = 0; $i < count($this->items); $i++) {
                $menu_string .= $this->items[$i]->get();
            }

            $menu_string .= "choose selected && goto \${selected}\n";

            for($i = 0; $i < count($this->items); $i++) {
                if(get_class($this->items[$i]) === "Yurifag\IPXE\MenuItem") {
                    $menu_string .= $this->items[$i]->get_command();
                }
                else if(get_class($this->items[$i]) === "Yurifag\IPXE\Menu") {
                    $menu_string .= $this->items[$i]->get_script(131);
                }
            }

            return $menu_string;
        }

    }
?>
