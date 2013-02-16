<?php

$RU_data =<<<SQL
SELECT keyword.id as `id`, keyword.account_id as `account_id`, keyword.keyword as `keyword`, campaign.id as `camp_id`, campaign.name as `campaign_name`, keyword.adgroup_id as `adgr_id`, adgroup.name as `adgroup_name`, keyword.max_cpc as `max_cpc`, metrics.date as `metrics_date`, IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, ROUND(IFNULL(SUM(metrics.clicks) / SUM(metrics.impressions) * 100, 0), 2) as `ctr`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2) as `average_cpc`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.conversions_many_per_click), 0), 2) as `cost_per_conversions_many`, ROUND(IFNULL(SUM(metrics.conversions_many_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_many_rate`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_many_per_click), 0), 2) as `total_conversions_value_per_conversions_many`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, ROUND(IFNULL(SUM(metrics.cost)/ SUM(metrics.conversions_one_per_click), 0), 2) as `cost_per_conversions`, ROUND(IFNULL(SUM(metrics.conversions_one_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_rate`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.cost) * 100, 0), 2) as `total_conversions_value_per_cost`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.clicks), 0), 2) as `total_conversions_value_per_clicks`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_one_per_click), 0), 2) as `total_conversions_value_per_conversions`, keyword.first_page_cpc as `first_page_cpc`, ROUND(IFNULL(SUM(keyword.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, ELT(FIELD(LOWER(keyword.match_type), '0','1','2','3'), 'Exact','Broad','Broad Match','Phrase') as `match_type`, ELT(FIELD(LOWER(keyword.status), '0','1','2','3'), 'Active','Paused','Deleted','Hold') as `status`, ELT(FIELD(LOWER(keyword.publish_status), '0','1','2','3'), 'New','Modified','Deleted','Not Changed') as `publish_status`, meta.created_at as `creation_time`, IFNULL((((keyword.max_cpc)-(ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2)))/(keyword.max_cpc))*100, 0) as `headroom`, IFNULL((IFNULL(SUM(metrics.total_conversions_value), 0))-(IFNULL(SUM(metrics.cost), 0)), 0) as `pcco_profit`, IFNULL((keyword.first_page_cpc), 0) as `fg`, campaign_unlinked.id IS NULL as `is_active`
FROM keyword
LEFT JOIN keyword_metrics as `metrics` ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id
LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id
LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
LEFT JOIN keyword_meta`meta` ON meta.keyword_id = keyword.id AND meta.adgroup_id = keyword.adgroup_id
WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
GROUP BY keyword.id,keyword.adgroup_id
ORDER BY NULL
LIMIT 0, 20
SQL;

$RU_data2 =<<<SQL
SELECT keyword.id as `id`, keyword.account_id as `account_id`, keyword.keyword as `keyword`, campaign.id as `camp_id`, campaign.name as `campaign_name`, keyword.adgroup_id as `adgr_id`, adgroup.name as `adgroup_name`, keyword.max_cpc as `max_cpc`, metrics.date as `metrics_date`, IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, ROUND(IFNULL(SUM(metrics.clicks) / SUM(metrics.impressions) * 100, 0), 2) as `ctr`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2) as `average_cpc`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.conversions_many_per_click), 0), 2) as `cost_per_conversions_many`, ROUND(IFNULL(SUM(metrics.conversions_many_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_many_rate`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_many_per_click), 0), 2) as `total_conversions_value_per_conversions_many`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, ROUND(IFNULL(SUM(metrics.cost)/ SUM(metrics.conversions_one_per_click), 0), 2) as `cost_per_conversions`, ROUND(IFNULL(SUM(metrics.conversions_one_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_rate`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.cost) * 100, 0), 2) as `total_conversions_value_per_cost`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.clicks), 0), 2) as `total_conversions_value_per_clicks`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_one_per_click), 0), 2) as `total_conversions_value_per_conversions`, keyword.first_page_cpc as `first_page_cpc`, ROUND(IFNULL(SUM(keyword.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, ELT(FIELD(LOWER(keyword.match_type), '0','1','2','3'), 'Exact','Broad','Broad Match','Phrase') as `match_type`, ELT(FIELD(LOWER(keyword.status), '0','1','2','3'), 'Active','Paused','Deleted','Hold') as `status`, ELT(FIELD(LOWER(keyword.publish_status), '0','1','2','3'), 'New','Modified','Deleted','Not Changed') as `publish_status`, meta.created_at as `creation_time`, IFNULL((((keyword.max_cpc)-(ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2)))/(keyword.max_cpc))*100, 0) as `headroom`, IFNULL((IFNULL(SUM(metrics.total_conversions_value), 0))-(IFNULL(SUM(metrics.cost), 0)), 0) as `pcco_profit`, IFNULL((keyword.first_page_cpc), 0) as `fg`, campaign_unlinked.id IS NULL as `is_active`
FROM keyword
LEFT JOIN keyword_metrics as `metrics` ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id
LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id
LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
LEFT JOIN keyword_meta`meta` ON meta.keyword_id = keyword.id AND meta.adgroup_id = keyword.adgroup_id
WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364') AND keyword.publish_status != 3
GROUP BY keyword.id,keyword.adgroup_id
ORDER BY NULL
LIMIT 0, 20
SQL;


$RU_total =<<<SQL
SELECT COUNT(id) FROM (
    SELECT keyword.id
    FROM keyword
    LEFT JOIN keyword_metrics as `metrics` ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id
    LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id
    LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
    LEFT JOIN keyword_meta as `meta` ON meta.keyword_id = keyword.id AND meta.adgroup_id = keyword.adgroup_id
    WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
    GROUP BY keyword.id,keyword.adgroup_id
) as temp
SQL;

$RU_total2 =<<<SQL
SELECT COUNT(DISTINCT keyword.adgroup_id, keyword.id)
FROM keyword
LEFT JOIN keyword_metrics as `metrics` ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id
LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id
LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
LEFT JOIN keyword_meta as `meta` ON meta.keyword_id = keyword.id AND meta.adgroup_id = keyword.adgroup_id
WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
GROUP BY keyword.id,keyword.adgroup_id
SQL;

$RU_total3 =<<<SQL
SELECT COUNT(*)
FROM keyword
LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id
LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = adgroup.campaign_id
WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
GROUP BY keyword.id,keyword.adgroup_id
SQL;
$RU_total4 =<<<SQL
SELECT
    COUNT(DISTINCT keyword.id, keyword.adgroup_id)
FROM keyword
LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id
LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = adgroup.campaign_id
WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
SQL;

$RU_metrics_total =<<<SQL
SELECT
    metrics.date as `metrics_date`, IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, ROUND(IFNULL(SUM(metrics.clicks) / SUM(metrics.impressions) * 100, 0), 2) as `ctr`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2) as `average_cpc`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.conversions_many_per_click), 0), 2) as `cost_per_conversions_many`, ROUND(IFNULL(SUM(metrics.conversions_many_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_many_rate`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_many_per_click), 0), 2) as `total_conversions_value_per_conversions_many`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, ROUND(IFNULL(SUM(metrics.cost)/ SUM(metrics.conversions_one_per_click), 0), 2) as `cost_per_conversions`, ROUND(IFNULL(SUM(metrics.conversions_one_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_rate`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.cost) * 100, 0), 2) as `total_conversions_value_per_cost`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.clicks), 0), 2) as `total_conversions_value_per_clicks`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_one_per_click), 0), 2) as `total_conversions_value_per_conversions`, ROUND(IFNULL(SUM(metrics.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, IFNULL((IFNULL(SUM(metrics.total_conversions_value), 0))-(IFNULL(SUM(metrics.cost), 0)), 0) as `pcco_profit`,
    COUNT(*) as `totalRows`
FROM (
    SELECT
        IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(keyword.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, '0000-00-00' as `date`
    FROM keyword_metrics as metrics
    LEFT JOIN keyword ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
    WHERE keyword.account_id IN ('364') AND campaign_unlinked.id IS NULL
    GROUP BY metrics.adgroup_id, metrics.keyword_id

) as metrics

SQL;

//$RU_metrics_subtotal =<<<SQL
//SELECT
//    metrics.date as `metrics_date`, IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, ROUND(IFNULL(SUM(metrics.clicks) / SUM(metrics.impressions) * 100, 0), 2) as `ctr`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.clicks), 0), 2) as `average_cpc`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, ROUND(IFNULL(SUM(metrics.cost) / SUM(metrics.conversions_many_per_click), 0), 2) as `cost_per_conversions_many`, ROUND(IFNULL(SUM(metrics.conversions_many_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_many_rate`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_many_per_click), 0), 2) as `total_conversions_value_per_conversions_many`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, ROUND(IFNULL(SUM(metrics.cost)/ SUM(metrics.conversions_one_per_click), 0), 2) as `cost_per_conversions`, ROUND(IFNULL(SUM(metrics.conversions_one_per_click) / SUM(metrics.clicks) * 100, 0), 2) as `conversions_rate`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.cost) * 100, 0), 2) as `total_conversions_value_per_cost`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.clicks), 0), 2) as `total_conversions_value_per_clicks`, ROUND(IFNULL(SUM(metrics.total_conversions_value) / SUM(metrics.conversions_one_per_click), 0), 2) as `total_conversions_value_per_conversions`, ROUND(IFNULL(SUM(metrics.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, IFNULL((IFNULL(SUM(metrics.total_conversions_value), 0))-(IFNULL(SUM(metrics.cost), 0)), 0) as `pcco_profit`,
//    COUNT(*) as `totalRows`
//FROM (
//    SELECT
//        IFNULL(SUM(metrics.clicks), 0) as `clicks`, IFNULL(SUM(metrics.impressions), 0) as `impressions`, IFNULL(SUM(metrics.cost), 0) as `cost`, ROUND(IFNULL(SUM(metrics.average_position * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `average_position`, IFNULL(SUM(metrics.conversions_many_per_click), 0) as `conversions_many_per_click`, IFNULL(SUM(metrics.internal_conversions_count), 0) as `internal_conversions_count`, IFNULL(SUM(metrics.conversions_one_per_click), 0) as `conversions_one_per_click`, IFNULL(SUM(metrics.total_conversions_value), 0) as `total_conversions_value`, IFNULL(SUM(metrics.internal_conversions_value), 0) as `internal_conversions_value`, ROUND(IFNULL(SUM(keyword.quality_score * metrics.impressions) / SUM(metrics.impressions), 0), 2) as `quality_score`, '0000-00-00' as `date`
//    FROM keyword_metrics as metrics
//    LEFT JOIN keyword ON metrics.adgroup_id = keyword.adgroup_id AND metrics.keyword_id = keyword.id LEFT JOIN adgroup ON adgroup.id = keyword.adgroup_id LEFT JOIN campaign ON campaign.id = adgroup.campaign_id LEFT JOIN campaign_unlinked ON campaign_unlinked.campaign_id = campaign.id
//    WHERE campaign_unlinked.id IS NULL AND keyword.account_id IN ('364')
//    GROUP BY metrics.adgroup_id, metrics.keyword_id
//
//) as metrics
//SQL;

return array(
    'RU_data' => $RU_data,
    'RU_data2' => $RU_data2,
    'RU_total' => $RU_total,
    'RU_total2' => $RU_total2,
    'RU_total3' => $RU_total3,
    'RU_total4' => $RU_total4,
    'RU_metrics_total' => $RU_metrics_total,
);