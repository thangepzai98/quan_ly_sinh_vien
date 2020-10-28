<?php
namespace App\Helpers;

class Helper {

    public static function getHtmlEditAndDelete($id, $urlEdit, $urlDelete, $editMethod = 'PUT', $deleteMethod = 'DELETE', $deleteTitle = '', $template = '')
    {
        $html = '<div class="btn-group btn-group-xs">';
            $html .= '<a href="#" title="Sửa" class="btn btn-inverse editRecord" data-toggle="modal" data-target="#editModal" data-method="' . $editMethod . '" data-url="' . $urlEdit . '" data-id="' . $id . '"><i class="fa fa-pencil icon-only"></i></a>';
            $html .= '<a href="javascript:void(0)" class="btn btn-danger removeRecord" title="Xóa" data-method="' . $deleteMethod . '" data-url="' . $urlDelete . '" data-id="' . $id . '" data-delete-title="' . $deleteTitle . '" data-template="' . $template . '"><i class="fa fa-times icon-only"></i></a>';
        $html .= '</div>';
        return $html;
    }

    public static function getHtmlEdit($id, $urlEdit, $editMethod = 'PUT')
    {
        $html = '<div class="btn-group btn-group-xs">';
            $html .= '<a href="#" title="Sửa" class="btn btn-inverse editRecord" data-toggle="modal" data-target="#editModal" data-method="' . $editMethod . '" data-url="' . $urlEdit . '" data-id="' . $id . '"><i class="fa fa-pencil icon-only"></i></a>';
        $html .= '</div>';
        return $html;
    }
}