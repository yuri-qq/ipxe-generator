<?php
namespace Yurifag\IPXE;
require_once("autoload.php");

$ipxe = new IPXE([
    "screen_width" => 107,
    "screen_height" => 768,
    "background_img_url" => "http://".$_SERVER["SERVER_ADDR"].":".$_SERVER["SERVER_PORT"]."/ipxe/ipxe.png",
    "netboot_base_url" => "http://".$_SERVER["SERVER_ADDR"].":".$_SERVER["SERVER_PORT"]."/ipxe/"
]);

$script = $ipxe->get_script(
    $ipxe->create_element("menu", "iPXE boot menu", [
        $ipxe->create_element("menu", "choose OS", [
            $ipxe->create_element("menuitem", "GParted", [
                $ipxe->create_element("command", "kernel", [
                    $ipxe->netboot_base_url."netboot/gparted/amd64/vmlinuz",
                    "initrd=initrd.img",
                    "boot=live",
                    "config",
                    "components",
                    "union=overlay",
                    "username=user",
                    "noswap",
                    "noeject",
                    "ip=",
                    "vga=788",
                    "fetch=".$ipxe->netboot_base_url."netboot/gparted/amd64/filesystem.squashfs"
                ]),
                $ipxe->create_element("command", "initrd", $ipxe->netboot_base_url."netboot/gparted/amd64/initrd.img"),
                $ipxe->create_element("command", "boot")
            ]),
            $ipxe->create_element("menu", "Debian", [
                $ipxe->create_element("menuitem", "amd64", [
                    $ipxe->create_element("command", "kernel", [
                        $ipxe->netboot_base_url."netboot/debian/amd64/linux",
                        "initrd=initrd.gz",
                        "initrd=preseed.cfg"
                    ]),
                    $ipxe->create_element("command", "initrd", $ipxe->netboot_base_url."netboot/debian/amd64/initrd.gz"),
                    $ipxe->create_element("command", "initrd", [
                        $ipxe->netboot_base_url."preseed.cfg",
                        "preseed.cfg"
                    ]),
                    $ipxe->create_element("command", "boot")
                ]),
            ])
        ]),
        $ipxe->create_element("menuitem", "Exit", $ipxe->create_element("command", "exit", "0"))
    ])
);

echo $script;
?>
