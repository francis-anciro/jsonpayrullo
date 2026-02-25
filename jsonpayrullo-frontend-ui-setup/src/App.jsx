import { HashRouter as Router, Routes, Route } from 'react-router-dom';
import Login from './components/Login';
import Navbar from './components/Navbar';
import Home from './components/Home';
import EmployeeList from './components/EmployeeList'; 
import AddEmployee from './components/AddEmployee';   
import EditEmployee from './components/EditEmployee'; 
import Attendance from './components/Attendance';
import Payroll from './components/Payroll';             
import PeriodDetail from './components/PeriodDetail';   
import Analytics from './components/Analytics';
import Footer from './components/Footer'; 

// Placeholder for Page 3
const Payslip = () => <div className="text-white p-8">Payslip Document View</div>;
import { Navigate } from 'react-router-dom';

const ProtectedRoute = ({ children }) => {
  const isAuthenticated = localStorage.getItem('user');

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  return children;
};
function App() {
  return (
      <Router>
        <Routes>
          {/* Public Route */}
          <Route path="/login" element={<Login />} />

          {/* Protected Routes */}
          <Route path="/*" element={
            <ProtectedRoute>
              <div className="flex flex-col min-h-screen bg-[#0a0a0a] relative">
                <Navbar />
                <main className="flex-grow">
                  <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/employees" element={<EmployeeList />} />
                    <Route path="/employees/add" element={<AddEmployee />} />
                    <Route path="/employees/edit/:id" element={<EditEmployee />} />
                    <Route path="/employees/attendance/:id" element={<Attendance />} />
                    <Route path="/payroll" element={<Payroll />} />
                    <Route path="/payroll/:id" element={<PeriodDetail />} />
                    <Route path="/payroll/payslip/:periodId/:empId" element={<Payslip />} />
                    <Route path="/analytics" element={<Analytics />} />
                  </Routes>
                </main>
                <Footer />
              </div>
            </ProtectedRoute>
          } />
        </Routes>
      </Router>
  );
}

export default App;