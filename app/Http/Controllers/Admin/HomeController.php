<?php

namespace App\Http\Controllers\Admin;

use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class HomeController
{
    public function index()
    {
        $settings1 = [
            'chart_title'        => 'Chart Source',
            'chart_type'         => 'pie',
            'report_type'        => 'group_by_relationship',
            'model'              => 'App\Models\Corrective',
            'group_by_field'     => 'source',
            'aggregate_function' => 'count',
            'filter_field'       => 'created_at',
            'filter_days'        => '7',
            'column_class'       => 'col-md-4',
            'entries_number'     => '5',
            'relationship_name'  => 'sources',
        ];

        $chart1 = new LaravelChart($settings1);

        $settings2 = [
            'chart_title'           => 'New CAR',
            'chart_type'            => 'number_block',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Corrective',
            'group_by_field'        => 'finding_date',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'filter_days'           => '14',
            'group_by_field_format' => 'd-m-Y',
            'column_class'          => 'col-md-4',
            'entries_number'        => '5',
        ];

        $settings2['total_number'] = 0;

        if (class_exists($settings2['model'])) {
            $settings2['total_number'] = $settings2['model']::when(isset($settings2['filter_field']), function ($query) use ($settings2) {
                if (isset($settings2['filter_days'])) {
                    return $query->where(
                        $settings2['filter_field'],
                        '>=',
                        now()->subDays($settings2['filter_days'])->format('Y-m-d')
                    );
                } else if (isset($settings2['filter_period'])) {
                    switch ($settings2['filter_period']) {
                        case 'week':
                            $start  = date('Y-m-d', strtotime('last Monday'));
                            break;
                        case 'month':
                            $start = date('Y-m') . '-01';
                            break;
                        case 'year':
                            $start  = date('Y') . '-01-01';
                            break;
                    }

                    if (isset($start)) {
                        return $query->where($settings2['filter_field'], '>=', $start);
                    }
                }
            })
                ->{$settings2['aggregate_function'] ?? 'count'}($settings2['aggregate_field'] ?? '*');
        }

        $settings3 = [
            'chart_title'        => 'Monthly Status',
            'chart_type'         => 'pie',
            'report_type'        => 'group_by_relationship',
            'model'              => 'App\Models\Corrective',
            'group_by_field'     => 'status',
            'aggregate_function' => 'count',
            'filter_field'       => 'created_at',
            'filter_days'        => '30',
            'column_class'       => 'col-md-4',
            'entries_number'     => '5',
            'relationship_name'  => 'status',
        ];

        $chart3 = new LaravelChart($settings3);

        $settings4 = [
            'chart_title'           => 'Corrective Action',
            'chart_type'            => 'latest_entries',
            'report_type'           => 'group_by_date',
            'model'                 => 'App\Models\Corrective',
            'group_by_field'        => 'finding_date',
            'group_by_period'       => 'day',
            'aggregate_function'    => 'count',
            'filter_field'          => 'created_at',
            'group_by_field_format' => 'd-m-Y',
            'column_class'          => 'col-md-12',
            'entries_number'        => '5',
            'fields'                => [
                'finding_date'   => '',
                'finding'        => '',
                'action'         => '',
                'target_date'    => '',
                'tanggung_jawab' => 'name',
                'status'         => 'status',
            ],
        ];

        $settings4['data'] = [];

        if (class_exists($settings4['model'])) {
            $settings4['data'] = $settings4['model']::latest()
                ->take($settings4['entries_number'])
                ->get();
        }

        if (!array_key_exists('fields', $settings4)) {
            $settings4['fields'] = [];
        }

        return view('home', compact('chart1', 'settings2', 'chart3', 'settings4'));
    }
}
