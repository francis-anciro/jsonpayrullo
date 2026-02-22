import React, { useState, useEffect } from 'react';
import { ArrowLeft, Loader2 } from 'lucide-react';
import { Link, useNavigate, useParams } from 'react-router-dom';

const InputField = ({ label, name, type = "text", placeholder, value, onChange }) => (
  <div className="flex flex-col gap-2">
    <label className="text-xs font-bold text-zinc-400 tracking-wider uppercase">{label}</label>
    <input
      type={type}
      name={name}
      value={value}
      onChange={onChange}
      placeholder={placeholder}
      className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-lime-400 focus:ring-1 focus:ring-lime-400 transition-all outline-none text-white placeholder-zinc-700"
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
      className="w-full px-4 py-3 bg-[#0a0a0a] border border-zinc-800 rounded-xl focus:border-lime-400 focus:ring-1 focus:ring-lime-400 transition-all outline-none text-white appearance-none cursor-pointer"
      required
    >
      {options.map((opt, index) => (
        <option key={index} value={opt}>{opt}</option>
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
    username: '', email: '', password: '', system_role: '',
    first_name: '', mid_name: '', last_name: '', phone: '', address: '', birthdate: '', hiredate: '',
    department: '', position: '', employment_type: '', basic_salary: '',
    shift: '', leave_type: '', leave_allocation: '', status: ''
  });

  useEffect(() => {
    setIsMounted(true);

    const fetchEmployeeData = async () => {
      try {
        console.log(`Fetching data for employee ID: ${id}`);
        await new Promise(resolve => setTimeout(resolve, 1000)); 
        
        const dummyDataFromBackend = {
          username: 'johndoe85', email: 'john.doe@company.com', password: '', system_role: 'HR Manager',
          first_name: 'John', mid_name: 'A.', last_name: 'Doe', phone: '+1 234 567 8900', address: '123 Main St, Springfield', 
          birthdate: '1985-05-12', hiredate: '2020-01-15',
          department: 'Human Resources', position: 'HR Manager', employment_type: 'Full-Time', basic_salary: '75000',
          shift: 'Day Shift', leave_type: 'Vacation', leave_allocation: '20', status: 'present'
        };

        setFormData(dummyDataFromBackend);
      } catch (error) {
        console.error("Failed to fetch employee:", error);
        alert("Could not load employee data.");
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
      console.log("Updating employee in backend:", formData);
      await new Promise(resolve => setTimeout(resolve, 800)); 
      alert("Employee details updated successfully!");
      navigate('/employees');
    } catch (error) {
      console.error("Failed to update employee:", error);
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
            <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg">
              Edit Employee
            </h1>
            <p className="text-lime-400 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
              Update existing personnel records
            </p>
          </div>
        </div>

        {isFetching ? (
          <div className="flex flex-col items-center justify-center py-32 gap-4 bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] shadow-xl">
            <Loader2 className="text-lime-400 animate-spin" size={48} />
            <p className="text-zinc-400 font-bold tracking-widest uppercase text-sm">Retrieving Database Records...</p>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="flex flex-col gap-6 animate-in fade-in duration-500">
            
            <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">
                User Account
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InputField label="Username" name="username" value={formData.username} onChange={handleChange} />
                <InputField label="Email Address" name="email" value={formData.email} onChange={handleChange} type="email" />
                <InputField label="New Password (Leave blank to keep current)" name="password" value={formData.password} onChange={handleChange} type="password" placeholder="••••••" />
                <SelectField 
                  label="System Role" 
                  name="system_role" 
                  value={formData.system_role}
                  onChange={handleChange}
                  options={['Employee', 'Admin', 'HR Manager', 'Manager']} 
                />
              </div>
            </div>

            <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">
                Employee Details
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <InputField label="First Name" name="first_name" value={formData.first_name} onChange={handleChange} />
                <InputField label="Middle Name" name="mid_name" value={formData.mid_name} onChange={handleChange} />
                <InputField label="Last Name" name="last_name" value={formData.last_name} onChange={handleChange} />
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InputField label="Phone Number" name="phone" value={formData.phone} onChange={handleChange} />
                <InputField label="Address" name="address" value={formData.address} onChange={handleChange} />
                <InputField label="Birthdate" name="birthdate" value={formData.birthdate} onChange={handleChange} type="date" />
                <InputField label="Hire Date" name="hiredate" value={formData.hiredate} onChange={handleChange} type="date" />
              </div>
            </div>

            <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">
                Position & Compensation
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <SelectField 
                  label="Department" 
                  name="department" 
                  value={formData.department}
                  onChange={handleChange}
                  options={['Operations & Tech (OPETECH)', 'Human Resources', 'Finance', 'IT', 'Sales']} 
                />
                <SelectField 
                  label="Position" 
                  name="position" 
                  value={formData.position}
                  onChange={handleChange}
                  options={['IT Support Specialist', 'Software Engineer', 'HR Manager', 'Accountant']} 
                />
                <SelectField 
                  label="Employment Type" 
                  name="employment_type" 
                  value={formData.employment_type}
                  onChange={handleChange}
                  options={['Full-Time', 'Part-Time', 'Contract', 'Internship']} 
                />
                <InputField label="Basic Salary" name="basic_salary" value={formData.basic_salary} onChange={handleChange} type="number" />
              </div>
            </div>

            <div className="bg-[#121212]/90 backdrop-blur-sm border border-zinc-800 rounded-[2rem] p-8 shadow-xl flex flex-col gap-6">
              <h2 className="text-lime-400 font-bold text-sm md:text-base tracking-widest uppercase border-b border-zinc-800 pb-4">
                Schedule & Leave
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <SelectField 
                  label="Shift" 
                  name="shift" 
                  value={formData.shift}
                  onChange={handleChange}
                  options={['Day Shift', 'Night Shift', 'Mid Shift']} 
                />
                <SelectField 
                  label="Leave Type" 
                  name="leave_type" 
                  value={formData.leave_type}
                  onChange={handleChange}
                  options={['Vacation', 'Sick Leave', 'Maternity/Paternity']} 
                />
                <InputField label="Leave Allocation" name="leave_allocation" value={formData.leave_allocation} onChange={handleChange} type="number" />
              </div>
            </div>

            <div className="flex justify-end gap-4 mt-4 mb-10">
              <Link
                to="/employees"
                className="py-4 px-10 rounded-xl font-bold tracking-widest text-sm transition-all duration-300 bg-transparent border border-zinc-700 text-zinc-300 hover:bg-zinc-800 hover:text-white uppercase"
              >
                Cancel
              </Link>
              
              <button
                type="submit"
                disabled={isLoading}
                className={`py-4 px-10 rounded-xl font-bold tracking-widest text-sm transition-all duration-300 border bg-lime-400/10 border-lime-400 text-lime-400 hover:bg-lime-500 hover:text-zinc-900 uppercase ${
                  isLoading ? 'opacity-50 cursor-not-allowed' : 'shadow-[0_0_15px_rgba(163,230,53,0.2)]'
                }`}
              >
                {isLoading ? 'Saving Changes...' : 'Save Changes'}
              </button>
            </div>

          </form>
        )}
      </div>
    </div>
  );
};

export default EditEmployee;