import React, { useState, useEffect } from 'react';
import { Link } from "react-router-dom";
import Swal from 'sweetalert2';

function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [user, setUser] = useState();

    useEffect(() => {
      const loggedInUser = localStorage.getItem("user");
      if (loggedInUser) {
        const foundUser = JSON.parse(loggedInUser);
        setUser(foundUser);
      }
    }, []);

    const submitHandler = (e) => {
        e.preventDefault();

        const formData = {
            email,
            password
        };

        fetch("https://adhera.kashkash.net/src/backend/login.php",{
            method: "POST",
            body: JSON.stringify(formData),
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if(data.status === "success")
            {
                sessionStorage.setItem('user',JSON.stringify(data));
                localStorage.setItem("user", JSON.stringify(data));
                Swal.fire("Success", data.message, "success");
                setTimeout(() => {
                    window.location.href = '/';
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
            <h2 className='text-center mb-1'>Login</h2>
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
            <button type='submit'>Login</button>

            <span className="mt-3">Don't have an account <Link to='/signup' className="btn btn-light">Signup</Link></span>
        </form>
    </div>
  )
}

export default Login