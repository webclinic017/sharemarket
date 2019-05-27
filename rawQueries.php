Q :- weekly average delivery query :-
ans:- SELECT week(date),date, AVG(`total_traded_qty`) VOLUME, AVG(per_delqty_to_trdqty) DEL FROM `stock_data` WHERE symbol = 'CANTABIL' GROUP by week(date), year(date) ORDER BY `DEL` DESC

DELETE FROM `dateinsert_report` WHERE report = 3;

SELECT avg(`open_interest`) FROM (SELECT * FROM oi_data WHERE symbol = 'infy' ORDER BY date desc LIMIT 0,15 ) s

Q: to take option watchList
Answer:-
SELECT
    oc.date,
    oe.expirydate,
    oc.strikeprice,
    oe.symbol,
    oc.callchnginoi,
    oc.putchnginoi,
    oc.calliv,
    oc.putiv,
    oc.ivratio,
    oc.callltp,
    oc.putltp
FROM
share.option_chain oc
JOIN
option_chain_expiry oe ON oc.oce_id = oe.id
WHERE
watchlist = 1
ORDER BY oc.id;
