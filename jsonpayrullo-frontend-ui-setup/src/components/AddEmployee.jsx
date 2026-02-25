import React, { useState, useEffect } from 'react';
import { ArrowLeft, Loader2 } from 'lucide-react';
import { Link, useNavigate } from 'react-router-dom';

const InputField = ({ label, name, type = "text", placeholder, value, onChange }) => (
    <div className="flex flex-col gap-2">
      <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">{label}</label>
      <input
          type={type}
          name={name}
          value={value}
          onChange={onChange}
          placeholder={placeholder}
          className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all outline-none text-white placeholder-zinc-700"
          required
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
          className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all outline-none text-white appearance-none cursor-pointer"
          required
      >
        {options.map((opt, index) => (
            <option key={index} value={opt.value}>{opt.label}</option>
        ))}
      </select>
    </div>
);

const DEPT_POSITIONS = {
  '1001': [
    { label: 'Art Director', value: '1001' },
    { label: 'Graphic Designer', value: '1002' },
    { label: 'Video Editor', value: '1003' },
    { label: 'Copywriter', value: '1004' },
  ],
  '1002': [
    { label: 'Social Media Manager', value: '1005' },
    { label: 'Content Strategist', value: '1006' },
    { label: 'Community Manager', value: '1007' },
  ],
  '1003': [
    { label: 'Account Executive', value: '1008' },
    { label: 'Account Manager', value: '1009' },
    { label: 'Client Success Specialist', value: '1010' },
  ],
  '1004': [
    { label: 'Web Developer', value: '1011' },
    { label: 'IT Support Specialist', value: '1012' },
    { label: 'Operations Manager', value: '1013' },
  ]
};

const AddEmployee = () => {
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState(false);
  const [isMounted, setIsMounted] = useState(false);
  const [departments, setDepartments] = useState([]);

  const [formData, setFormData] = useState({
    username: '',
    email: '',
    password: '',
    role: 'employee',
    first_name: '',
    middle_name: '',
    last_name: '',
    phone: '',
    address: '',
    birthdate: '',
    hire_date: '',
    department_id: '1001',
    position_id: '1001',
    employment_type: 'Full-time',
    basic_salary: '',
    shift_id: '1001'
  });

  useEffect(() => {
    setIsMounted(true);
    fetchDepartments();
  }, []);

  // --- NEW: Fetch Department Names from DB ---
  const fetchDepartments = async () => {
    try {
      const response = await fetch('http://localhost/JSONPayrullo/AddUser/getDepartments', {
        headers: { 'Accept': 'application/json' },
        credentials: 'include'
      });
      const data = await response.json();

      // Expected format from DB: { label: ["Name1", "Name2"], values: ["1001", "1002"] }
      const formattedDepts = data.label.map((name, index) => ({
        label: name,
        value: data.values[index].toString()
      }));

      setDepartments(formattedDepts);
    } catch (error) {
      console.error('Error fetching departments:', error);
      // Fallback in case API fails
      setDepartments([
        { label: 'Creative & Production', value: '1001' },
        { label: 'Content & Social Media', value: '1002' },
        { label: 'Accounts & Client Services', value: '1003' },
        { label: 'Operations & Tech', value: '1004' }
      ]);
    }
  };

  const handleChange = (e) => {
    const { name, value } = e.target;

    if (name === 'department_id') {
      const firstPosition = DEPT_POSITIONS[value] ? DEPT_POSITIONS[value][0].value : '';
      setFormData({
        ...formData,
        department_id: value,
        position_id: firstPosition
      });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const response = await fetch('http://localhost/JSONPayrullo/AddUser/addUser', {
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
        alert("Employee registered successfully!");
        navigate('/employees');
      } else {
        alert(`Error: ${result.response || 'Failed to register'}`);
      }
    } catch (error) {
      console.error("Failed to add employee:", error);
      alert("Network error. Please check your connection.");
    } finally {
      setIsLoading(false);
    }
  };

  return (
      <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-y-auto">
        {/* Background Decorative Gradients */}
        <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-teal-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
        <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-teal-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-opacity duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

        <div className={`relative z-10 w-full max-w-4xl flex flex-col transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>

          <div className="flex items-center gap-4 mb-8">
            <Link to="/employees" className="p-3 text-zinc-400 hover:text-white hover:bg-zinc-800 rounded-xl transition-colors bg-[#121212] border border-zinc-800 shadow-lg">
              <ArrowLeft size={24} />
            </Link>
            <div className="flex flex-col">
              <h1 className="text-2xl md:text-3xl font-black text-white uppercase drop-shadow-lg">Employee Registration</h1>
              <p className="text-teal-500 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">Onboard new personnel</p>
            </div>
          </div>

          <form onSubmit={handleSubmit} className="flex flex-col gap-6">
            {/* --- USER ACCOUNT --- */}
            <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-teal-500 font-bold text-sm tracking-widest uppercase border-b border-zinc-800 pb-4">User Account</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InputField label="Username" name="username" value={formData.username} onChange={handleChange} />
                <InputField label="Email Address" name="email" value={formData.email} onChange={handleChange} type="email" />
                <InputField label="Password" name="password" value={formData.password} onChange={handleChange} type="password" />
                <SelectField
                    label="System Role"
                    name="role"
                    value={formData.role}
                    onChange={handleChange}
                    options={[
                      { label: 'Employee', value: 'employee' },
                      { label: 'Manager', value: 'manager' },
                      { label: 'Admin', value: 'admin' }
                    ]}
                />
              </div>
            </div>

            {/* --- PERSONAL DETAILS --- */}
            <div className="bg-[#121212]/90 border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-teal-500 font-bold text-sm uppercase border-b border-zinc-800 pb-4">Personal Details</h2>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <InputField label="First Name" name="first_name" value={formData.first_name} onChange={handleChange} />
                <InputField label="Middle Name" name="middle_name" value={formData.middle_name} onChange={handleChange} />
                <InputField label="Last Name" name="last_name" value={formData.last_name} onChange={handleChange} />
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InputField label="Phone Number" name="phone" value={formData.phone} onChange={handleChange} />
                <InputField label="Address" name="address" value={formData.address} onChange={handleChange} />
                <InputField label="Birthdate" name="birthdate" value={formData.birthdate} onChange={handleChange} type="date" />
                <InputField label="Hire Date" name="hire_date" value={formData.hire_date} onChange={handleChange} type="date" />
              </div>
            </div>

            {/* --- POSITION & COMPENSATION --- */}
            <div className="bg-[#121212]/90 border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-teal-500 font-bold text-sm uppercase border-b border-zinc-800 pb-4">Position & Compensation</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {/* Dynamic Departments from DB */}
                <SelectField
                    label="Department"
                    name="department_id"
                    value={formData.department_id}
                    onChange={handleChange}
                    options={departments.length > 0 ? departments : [{ label: 'Loading...', value: '' }]}
                />

                <SelectField
                    label="Position"
                    name="position_id"
                    value={formData.position_id}
                    onChange={handleChange}
                    options={DEPT_POSITIONS[formData.department_id] || []}
                />

                <InputField label="Basic Salary" name="basic_salary" value={formData.basic_salary} onChange={handleChange} type="number" />
                <SelectField
                    label="Employment Type"
                    name="employment_type"
                    value={formData.employment_type}
                    onChange={handleChange}
                    options={[{ label: 'Full-time', value: 'Full-time' }, { label: 'Part-time', value: 'Part-time' }]}
                />
              </div>
            </div>

            {/* --- SCHEDULE SECTION --- */}
            <div className="bg-[#121212]/90 border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-teal-500 font-bold text-sm uppercase border-b border-zinc-800 pb-4">Schedule & Shift</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <SelectField
                    label="Assigned Shift"
                    name="shift_id"
                    value={formData.shift_id}
                    onChange={handleChange}
                    options={[
                      { label: 'Day Shift', value: '1001' },
                      { label: 'Afternoon Shift', value: '1002' },
                      { label: 'Night Shift', value: '1003' }
                    ]}
                />
              </div>
            </div>

            <div className="flex justify-end gap-4 mt-4 mb-10">
              <Link to="/employees" className="py-4 px-10 rounded-xl font-bold tracking-widest text-sm text-zinc-300 border border-zinc-700 hover:bg-zinc-800 hover:text-white uppercase transition-all">Cancel</Link>
              <button type="submit" disabled={isLoading} className={`py-4 px-10 rounded-xl font-bold tracking-widest text-sm border bg-teal-500/10 border-teal-500 text-teal-500 hover:bg-teal-600 hover:text-white uppercase transition-all ${isLoading ? 'opacity-50' : 'shadow-lg shadow-teal-500/20'}`}>
                {isLoading ? <Loader2 className="animate-spin" size={20} /> : 'Complete Registration'}
              </button>
            </div>
          </form>
        </div>
      </div>
  );
};

export default AddEmployee;