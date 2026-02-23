import React, { useState, useEffect } from 'react';
import { ArrowLeft, Loader2 } from 'lucide-react';
import { Link, useNavigate, useParams } from 'react-router-dom';

const InputField = ({ label, name, type = "text", placeholder, value, onChange, required = true }) => (
    <div className="flex flex-col gap-2">
      <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">{label}</label>
      <input
          type={type}
          name={name}
          value={value}
          onChange={onChange}
          placeholder={placeholder}
          className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-lime-400 focus:ring-1 focus:ring-lime-400 transition-all outline-none text-white placeholder-zinc-700"
          required={required}
      />
    </div>
);

const SelectField = ({ label, name, options, value, onChange }) => (
    <div className="flex flex-col gap-2">
      <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">{label}</label>
      <select
          name={name}
          value={value}
          onChange={onChange}
          className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-lime-400 focus:ring-1 focus:ring-lime-400 transition-all outline-none text-white appearance-none cursor-pointer"
          required
      >
        {options.map((opt, index) => (
            <option key={index} value={opt.value}>{opt.label}</option>
        ))}
      </select>
    </div>
);

const EditEmployee = () => {
  const navigate = useNavigate();
  const { id } = useParams();

  const [isFetching, setIsFetching] = useState(true);
  const [isLoading, setIsLoading] = useState(false);
  const [isMounted, setIsMounted] = useState(false);

  const [formData, setFormData] = useState({
    employee_code: '',
    username: '', // Added username to state
    email: '',
    password: '',
    role: '',
    first_name: '',
    last_name: '',
    phone: '',
    address: '',
    department_id: '',
    position_id: '',
    basic_salary: '',
    shift_id: ''
  });

  useEffect(() => {
    setIsMounted(true);

    const fetchEmployeeData = async () => {
      try {
        const response = await fetch(`http://localhost/JSONPayrullo/EditUser/index/${id}`, {
          method: 'GET',
          headers: { 'Accept': 'application/json' },
          credentials: 'include'
        });

        const result = await response.json();

        if (response.ok && result.user) {
          const user = result.user;
          setFormData({
            employee_code: user.employee_code,
            username: user.username, // Mapping username from DB
            email: user.email,
            password: '',
            role: user.role,
            first_name: user.first_name,
            last_name: user.last_name,
            phone: user.phone,
            address: user.address,
            department_id: user.Department_ID,
            position_id: user.Position_ID,
            basic_salary: user.basic_salary,
            shift_id: user.Shift_ID,
            employment_type: user.employment_type || 'Full-time'
          });
        } else {
          throw new Error(result.response || "User not found");
        }
      } catch (error) {
        console.error("Failed to fetch employee:", error);
        alert(error.message);
        navigate('/employees');
      } finally {
        setIsFetching(false);
      }
    };

    fetchEmployeeData();
  }, [id, navigate]);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const response = await fetch('http://localhost/JSONPayrullo/EditUser/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        credentials: 'include',
        body: JSON.stringify(formData),
      });

      const result = await response.json();

      if (response.ok && result.status === 'success') {
        alert("Employee details updated successfully!");
        navigate('/employees');
      } else {
        alert(`Error: ${result.response || 'Update failed'}`);
      }
    } catch (error) {
      console.error("Failed to update employee:", error);
      alert("Network error. Please check your connection.");
    } finally {
      setIsLoading(false);
    }
  };

  return (
      <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-y-auto">
        <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-lime-400/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
        <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-lime-400/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

        <div className={`relative z-10 w-full max-w-4xl flex flex-col transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>

          <div className="flex items-center gap-4 mb-8">
            <Link to="/employees" className="p-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors bg-[#121212] border border-zinc-800 shadow-lg">
              <ArrowLeft size={24} />
            </Link>
            <div className="flex flex-col">
              <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">Edit Employee</h1>
              <p className="text-lime-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">Editing Record: {id}</p>
            </div>
          </div>

          {isFetching ? (
              <div className="flex flex-col items-center justify-center py-32 gap-4 bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] shadow-xl">
                <Loader2 className="text-lime-400 animate-spin" size={48} />
                <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Retrieving Database Records...</p>
              </div>
          ) : (
              <form onSubmit={handleSubmit} className="flex flex-col gap-6">

                <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
                  <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">Account Access</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {/* Username added to the grid */}
                    <InputField label="Username" name="username" value={formData.username} onChange={handleChange} />
                    <InputField label="Email Address" name="email" value={formData.email} onChange={handleChange} type="email" />
                    <SelectField
                        label="System Role"
                        name="role"
                        value={formData.role}
                        onChange={handleChange}
                        options={[
                          { label: 'Employee', value: 'employee' },
                          { label: 'Admin', value: 'admin' }
                        ]}
                    />
                    <InputField
                        label="New Password"
                        name="password"
                        value={formData.password}
                        onChange={handleChange}
                        type="password"
                        placeholder="Leave blank to keep current"
                        required={false}
                    />
                  </div>
                </div>

                <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
                  <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">Personal Details</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <InputField label="First Name" name="first_name" value={formData.first_name} onChange={handleChange} />
                    <InputField label="Last Name" name="last_name" value={formData.last_name} onChange={handleChange} />
                    <InputField label="Phone" name="phone" value={formData.phone} onChange={handleChange} />
                    <InputField label="Address" name="address" value={formData.address} onChange={handleChange} />
                  </div>
                </div>

                <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
                  <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">Position & Pay</h2>
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <SelectField
                        label="Department"
                        name="department_id"
                        value={formData.department_id}
                        onChange={handleChange}
                        options={[
                          { label: 'Creative & Production', value: '1' },
                          { label: 'Content & Social Media', value: '2' },
                          { label: 'Accounts & Client Services', value: '3' },
                          { label: 'Operations & Tech', value: '4' }
                        ]}
                    />
                    <SelectField
                        label="Position"
                        name="position_id"
                        value={formData.position_id}
                        onChange={handleChange}
                        options={[
                          { label: 'IT Support Specialist', value: '12' },
                          { label: 'Web Developer', value: '11' },
                          { label: 'Art Director', value: '1' }
                        ]}
                    />
                    <InputField label="Basic Salary" name="basic_salary" value={formData.basic_salary} onChange={handleChange} type="number" />
                    <SelectField
                        label="Shift"
                        name="shift_id"
                        value={formData.shift_id}
                        onChange={handleChange}
                        options={[
                          { label: 'Day Shift', value: '1' },
                          { label: 'Night Shift', value: '2' }
                        ]}
                    />
                  </div>
                </div>

                <div className="flex justify-end gap-4 mt-4 mb-10">
                  <Link to="/employees" className="py-4 px-10 rounded-xl font-bold tracking-widest text-sm text-zinc-300 border border-zinc-700 hover:bg-zinc-800 hover:text-white uppercase">Cancel</Link>
                  <button type="submit" disabled={isLoading} className={`py-4 px-10 rounded-xl font-bold tracking-widest text-sm border bg-lime-400/10 border-lime-400 text-lime-400 hover:bg-lime-500 hover:text-zinc-900 uppercase ${isLoading ? 'opacity-50 cursor-not-allowed' : 'shadow-lg'}`}>
                    {isLoading ? 'Saving...' : 'Save Changes'}
                  </button>
                </div>
              </form>
          )}
        </div>
      </div>
  );
};

export default EditEmployee;