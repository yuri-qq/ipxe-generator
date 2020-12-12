<?php
    namespace Yurifag\IPXE;
    class MenuItem extends Item {
        private $commands;

        /**
         * item to be added to a Menu object
         * @param string $text     Text to be shown for this menu entry
         * @param array  $commands List of Command objects associated with this menu entry
         */
        public function __construct(string $text, array $commands = []) {
            parent::__construct($text);

            $this->commands = $commands;
        }

        /**
         * @return string iPXE script menu item string
         */
        public function get(): string {
            return "item ".$this->id." ".$this->text."\n";
        }

        /**
         * concatenate all Command objects
         * @return string iPXE script
         */
        protected function get_command(): string {
            $cmd_count = count($this->commands);
            if($cmd_count > 0) {
                $ipxe_script = ":".$this->id."\n";
                for ($i=0; $i < $cmd_count; $i++) {
                    $ipxe_script .= $this->commands[$i]->get();
                }
                return $ipxe_script;
            }
            else {
                return "";
            }
        }

    }
?>
