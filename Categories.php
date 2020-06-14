<?php

require_once 'woocommerce_connect.php';

class Categories
{
    static function getParentCategories() {
        global $woocommerce;
        $arr = [];
        $categories = $woocommerce->get('products/categories', ['per_page' => 100, 'hide_empty' => true, 'parent' => 0]);
        if ($woocommerce->http->getResponse()->getCode() == 200) {
            foreach ($categories as $category) {
                if ($category->parent == 0) {
                    $arr[] = $category->name;
                }
            }
        }
        return $arr;
    }

    static function getChildCategories($parentName) {
        global $woocommerce;
        $arr = [];
        $categoryId = self::getCategoryId($parentName);
        $categories = $woocommerce->get('products/categories', ['per_page' => 100, 'hide_empty' => true, 'parent' => $categoryId]);
        if ($woocommerce->http->getResponse()->getCode() == 200) {
            foreach ($categories as $category) {
                $arr[] = $category->name;
            }
        }
        return $arr;
    }

    static function getProducts($categoryName) {
        global $woocommerce;
        $arr = [];
        $categoryId = self::getCategoryId($categoryName);
        $products = $woocommerce->get('products', ['per_page' => 100, 'category' => $categoryId]);
        if ($woocommerce->http->getResponse()->getCode() == 200) {
            foreach ($products as $product) {
                $arr[] = $product->name;
            }
        }
        return $arr;
    }

    static function getCategoryId($name) {
        global $woocommerce;
        $categories = $woocommerce->get('products/categories', ['per_page' => 100]);
        if ($woocommerce->http->getResponse()->getCode() == 200) {
            foreach ($categories as $category) {
                if ($category->name == $name) {
                    return $category->id;
                }
            }
        }
        return "";
    }

}