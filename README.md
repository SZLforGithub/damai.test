# 大麥網路股份有限公司面試前測驗

#### GetAddress
接口: **POST** **/api/address**\
說明: 

Requests:
```json
{
    "address": "台北市中正區羅斯福路三段210巷8弄7之13号1F"
}
``` 
Response:
```json
{
    "zip": 100,
    "city": "台北市",
    "area": "中正區",
    "road": "羅斯福路三段",
    "lane": 210,
    "alley": 8,
    "no": 7,
    "floor": 1,
    "address": "",
    "filename": "100",
    "latitude": 25.0181072,
    "lontitue": 121.5294395,
    "full_address": "100台灣台北市中正區羅斯福路三段210巷8弄7號1F"
}
```