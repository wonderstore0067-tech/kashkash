import React, { useEffect, useState } from "react";
import Header from "./Header";
// import { Link } from "react-router-dom";


function TransactionList() {
  const [transactions, setTransactions] = useState([]);
//   const [userTransactions, setUserTransactions] = useState([]);

  useEffect(() => {
    // Fetch data from the database or API
    fetch("https://adhera.kashkash.net/src/backend/transaction_list.php")
      .then((response) => response.json())
      .then((data) => {
        setTransactions(data);
        console.log(data);
      })
      .catch((error) => {
        console.error(error);
      });
  }, []);



  return (
    <div>
      <Header />
      <div className="transactions mt-3">
        <h2 className="text-center">Transaction List</h2>
        <table className="table table-striped">
            <thead className="thead-light">
                <tr>
                    <th>Sender Account</th>
                    <th>Receiver Account</th>
                    <th>Receiver Name</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {transactions.map((transaction) => (
                    <tr key={transaction.id}>
                        <td>{transaction.sender_account}</td>
                        <td>{transaction.receiver_account}</td>
                        <td>{transaction.receiver_name}</td>
                        <td>{transaction.amount}</td>
                        <td>{transaction.status}</td>
                    </tr>
                ))}
            </tbody>
        </table>
      </div>
    </div>
  );
}

export default TransactionList;
