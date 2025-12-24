import React, { useState } from 'react';
import { Link } from "react-router-dom";
import Swal from 'sweetalert2';

function Signup() {
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const submitHandler = (e) => {
        e.preventDefault();

        const formData = {
            name,
            email,
            password
        };

        fetch("https://adhera.kashkash.net/src/backend/signup.php",{
            method: "POST",
            body: JSON.stringify(formData),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if(data.status === "success")
            {
                Swal.fire("Success", data.message, "success");
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2500);
            }
            else{
                Swal.fire("Error", data.message, "error");
            }
        })
        .catch((error) => {
            Swal.fire("Error", "An error occurred", "error");
        });
    };

  return (
    <div>
        <form className="form-container mt-3" onSubmit={submitHandler}>
        <h2 className='text-center mb-1'>Signup</h2>
        <label htmlFor="name">Name:</label>
            <input type="text" 
                className='form-control' 
                id="name"
                value={name}
                onChange={(e) => setName(e.target.value)}
            />
            <label htmlFor="email">Email:</label>
            <input type="email" 
                className='form-control' 
                id="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
            />
            <label htmlFor="password">Password:</label>
            <input type="password" 
                className='form-control' 
                id="password" 
                value={password}
                onChange={(e) => setPassword(e.target.value)}
            />
            <button type='submit'>Signup</button>

            <span className="mt-3">Already have an account <Link to='/login' className="btn btn-light">Login</Link></span>
        </form>
    </div>
  )
}

export default Signup