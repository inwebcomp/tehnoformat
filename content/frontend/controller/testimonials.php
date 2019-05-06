<?php

class controller_testimonials
{
    public function items()
    {
        $page = $_GET['page'] ?? 1;

        $content = Testimonial::getAll(true, ['onPage' => 2, 'page_is' => $page, 'orderDirection' => 'DESC'], true);

        foreach ($content['items'] as &$item) {
            $item['date'] = Utils::timeToHumanDate(strtotime($item['created']));

            if ($product = Product::one($item['product_ID']))
                $item['product'] = $product->toArray();
        }

        if (Application::wantsJson())
            return ['data' => $content['items']];

        return [
            'testimonials' => $content['items'],
            'pagination' => [
                'current' => ($page * 2 - 1) . '-' . ($page * 2),
                'max' => $content['select']['num']
            ]
        ];
    }
}