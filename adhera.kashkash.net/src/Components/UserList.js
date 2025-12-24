import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import Swal from "sweetalert2";
import Header from "./Header";

function UserList() {
  const [users, setUsers] = useState([]);
  
    useEffect(() => {
    // Fetch data from the database or API
    fetch(`https://adhera.kashkash.net/src/backend/user_list.php`)
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setUsers(data);
      })
      .catch((error) => {
        console.error(error);
      });
  }, []);
  
  const handleDelete = (id) => {
    // Display the confirmation dialog using SweetAlert2
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      // If the user clicks "OK" in the confirmation dialog
      if (result.isConfirmed) {
    // Make an API call to the backend to delete the record with the given 'id'
    fetch(`https://adhera.kashkash.net/src/backend/delete_user.php?id=${id}`)
      .then((response) => response.json())
      .then((data) => {
        // Handle the response from the backend
        console.log(data);
        if (data.status === "success") {
          Swal.fire("Success", data.message, "success");
          // If the deletion is successful, update the 'users' state by removing the deleted user from the array
          setUsers((prevUsers) => prevUsers.filter((user) => user.id !== id));
        } else {
          // Handle the error case
          // You can show an error message or perform any other appropriate action here
          console.error("Error deleting user");
        }
      })
      .catch((error) => {
        console.error(error);
      });
    }
  });
  };


  return (
    <div>
      <Header />
      <div className="records mt-3">
        <h2 className="text-center">User List</h2>
        <p className="text-center fs-5">Select a user to send hedera</p>
        {users.map((user) => (
            <div className="row" key={user.id}>
            <div className="col-8">
                <Link to={`/send-amount/${user.id}`} className="text-decoration-none text-dark bg-white w-100 user-btn">
                    <h4>{user.name}</h4>
                </Link>
            </div>
            <div className="col-4">
              <Link to={`/user-edit/${user.id}`} className="btn btn-info">
                  Edit
              </Link>
              <button className="btn btn-danger" onClick={() => handleDelete(user.id)}>
                Delete
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

export default UserList;
