Q :- weekly average delivery query :-
ans:- SELECT week(date),date, AVG(`total_traded_qty`) VOLUME, AVG(per_delqty_to_trdqty) DEL FROM `stock_data` WHERE symbol = 'CANTABIL' GROUP by week(date), year(date) ORDER BY `DEL` DESC

DELETE FROM `dateinsert_report` WHERE report = 3;

SELECT avg(`open_interest`) FROM (SELECT * FROM oi_data WHERE symbol = 'infy' ORDER BY date desc LIMIT 0,15 ) s

Q: to take option watchList
SELECT date, oc.expirydate,strikeprice, callchnginoi,putchnginoi,calliv, putiv, ivratio, oc.symbol FROM `option_chain` join option_chain_expiry oc on oce_id = oc.id where watchlist = 1
ORDER BY `date`  DESC

Q:- to study option chain of particular stocks
SELECT oc.date,oc.strikeprice,oc.callchnginoi,oc.putchnginoi,oc.calliv,oc.putiv,oc.ivratio,oc.callltp,oc.putltp FROM option_chain as oc join option_chain_expiry as oce on oc.oce_id = oce.id
where oce.symbol = 'TCS' AND oce.expirydate = '2019-05-30' order by strikeprice,date

Q:- CHECK particular script PCR
SELECT oce.symbol, oce.expirydate, pcr.*  FROM `pcr` join option_chain_expiry oce on pcr.oce_id = oce.id  AND oce.symbol LIKE 'NIFTY'
ORDER BY OCE.symbol , `pcr`.`id` DESC

Q:- Check particular strike price data as per expiry date and trading date range.
select oc.symbol, oc.expirydate,strikeprice, date, callltp, putltp ,callchnginoi,putchnginoi,calliv, putiv, ivratio FROM `option_chain` join option_chain_expiry oc on oce_id = oc.id
where symbol = 'IDEA' AND strikeprice = 12 AND date BETWEEN '2019-04-12' and '2019-04-31' AND oc.expirydate = '2019-04-25' ORDER BY `date` ASC


Q: Watchlist for open interest avg 15 days and more
SELECT * FROM `oi_data` WHERE `watchlist` = 1 ORDER BY date ASC;
SELECT * FROM `oi_data` WHERE `watchlist` =1 AND `symbol` = 'INDIACEM'
