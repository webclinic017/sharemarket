Q :- weekly average delivery query :-
ans:- SELECT week(date),date, AVG(`total_traded_qty`) VOLUME, AVG(per_delqty_to_trdqty) DEL FROM `stock_data` WHERE symbol = 'CANTABIL' GROUP by week(date), year(date) ORDER BY `DEL` DESC

DELETE FROM `dateinsert_report` WHERE report = 3;

SELECT avg(`open_interest`) FROM (SELECT * FROM oi_data WHERE symbol = 'infy' ORDER BY date desc LIMIT 0,15 ) s

Q: to take option watchList
SELECT date, oc.expirydate,strikeprice, callchnginoi,putchnginoi,calliv, putiv, ivratio, oc.symbol FROM `option_chain` join option_chain_expiry oc on oce_id = oc.id where watchlist = 1
ORDER BY `date`  DESC
