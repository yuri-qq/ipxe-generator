<?php
    namespace Yurifag\IPXE;
    class Command {
        protected $cmd;
        protected $params;

        /**
         * creates an iPXE command
         * @param string $cmd    iPXE command
         * @param array  $params command parameters
         */
        public function __construct(string $cmd, array $params = []) {
            $this->cmd = $cmd;
            $this->params = $params;
        }

        /**
         * @return string iPXE script command
         */
        public function get(): string {
            $ipxe_script = $this->cmd;
            for($i = 0; $i < count($this->params); $i++) {
                $ipxe_script .= " ".$this->params[$i];
            }

            return $ipxe_script."\n";
        }
    }
?>
