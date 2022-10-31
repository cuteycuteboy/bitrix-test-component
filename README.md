Пример вызова компонета
```php
$APPLICATION->IncludeComponent(
	"test:list",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "N",
		"IBLOCK_ID" => "1",
		"IBLOCK_TYPE" => "news",
		"VOTE_COUNT_ID" => "9"
	)
);
```
