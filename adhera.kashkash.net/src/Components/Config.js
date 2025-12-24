import React, { useState, useEffect } from "react";
import { Link } from "react-router-dom";
import Swal from "sweetalert2";
import Header from "./Header";

function Config() {
  const [settings, setSettings] = useState([]);
  
    useEffect(() => {
    // Fetch data from the database or API
    fetch(`https://adhera.kashkash.net/src/backend/settings.php`)
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setSettings(data);
      })
      .catch((error) => {
        console.error(error);
      });
  }, []);
  
  const handleDelete = (id, status) => {
    // Display the confirmation dialog using SweetAlert2

    status = (status == 0) ? 1 : 0;
    
    Swal.fire({
      title: "Are you sure?",
      text: "You want to change this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#d33",
      cancelButtonColor: "#3085d6",
      confirmButtonText: "Yes, change it!"
    }).then((result) => {
      // If the user clicks "OK" in the confirmation dialog
      if (result.isConfirmed) {
    // Make an API call to the backend to delete the record with the given 'id'
    fetch(`https://adhera.kashkash.net/src/backend/delete_config.php?id=${id}&status=${status}`)
      .then((response) => response.json())
      .then((data) => {
        // Handle the response from the backend
        console.log(data);
        if (data.status === "success") {
          Swal.fire("Success", data.message, "success");
          // setTimeout(() => {
          //   window.location.reload();
          // }, 3000);
          
          // setSettings((prevUsers) => prevUsers.filter((user) => user.id !== id));
        } else {
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
      <div className="config mt-3">
        <div className="row">
          <div className="col-10 text-center">
            <h3 className="mt-2 mr-5">CREATE A COIN</h3>
          </div>
          <div className="col-2 text-center">
            <Link to={`/add-config`} className="btn btn-info ml-5">
              Add
            </Link>
          </div>
        </div>

        <div className="table-responsive">
        <table className="table table-striped table-responsive">
            <thead className="thead-light">
                <tr>
                    <th className="column3">Token Name</th>
                    <th className="column3">Token Symbol</th>
                    <th className="column3">Initial Supply</th>
                    <th className="column3">Account Id</th>
                    {/* <th className="column1">Private Key</th>
                    <th className="column1">Public Key</th>
                    <th className="column1">Hex Private Key</th> */}
                    <th className="column3">Added By</th>
                    <th className="column3">Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {settings.map((setting) => (
                    <tr key={setting.id}>
                        <td className="column1">{setting.token_name}</td>
                        <td className="column1">{setting.token_symbol}</td>
                        <td className="column1">{setting.initial_supply}</td>
                        <td className="column1">{setting.account_id}</td>
                        {/* <td className="column1">{setting.private_key}</td>
                        <td className="column1">{setting.public_key}</td>
                        <td className="column1">{setting.hex_private_key}</td> */}
                        <td className="column1">{setting.name}</td>
                        <td>
                        {/* {setting.active == 0 ? 
                          <button className="btn btn-success" onClick={() => handleDelete(setting.id, setting.status)}>
                            Activate
                          </button> : 
                          <button className="btn btn-danger" onClick={() => handleDelete(setting.id, setting.status)}>
                            Deactivate
                          </button>
                        } */}

{/* <div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode" onClick={() => handleDelete(setting.id, setting.active)} checked />
  <label class="form-check-label" for="mySwitch">Dark Mode</label>
</div> */}

                              {setting.active == 1 ? 
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode" onClick={() => handleDelete(setting.id, setting.active)} checked />
                                  </div> :
                                  <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="mySwitch" name="darkmode" onClick={() => handleDelete(setting.id, setting.active)} />
                                  </div>
                              }
                        </td>
                        <td>
                          <Link to={`https://hashscan.io/testnet/token/${setting.token_no}?p=1&k=${setting.account_id}`} className="btn btn-secondary mx-2" target="_blank" rel="noopener noreferrer">
                              Details
                          </Link>

                          <Link to={`/edit-config/${setting.id}`} className="btn btn-info mx-2">
                              Edit
                          </Link>
                          {/* <button className="btn btn-danger" onClick={() => handleDelete(setting.id)}>
                            Delete
                          </button> */}
                        </td>

                    </tr>
                ))}
            </tbody>
        </table>
        </div>
      </div>
    </div>
     
  );
}

export default Config;
