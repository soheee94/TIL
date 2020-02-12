# DATEDIFF

두 날짜 간의 차이를 가져올 때 사용하는 함수

## 사용법

```MYSQL
DATEDIFF(날짜1, 날짜2);
```

날짜1 - 날짜2

## 활용

가장 가까운 날짜 가져오기

```mySQL
SELECT *
FROM `TABLE_NAME`
ORDER BY ABS(DATEDIFF(now(), `DATETIME`)) DESC
LIMIT 1
```
