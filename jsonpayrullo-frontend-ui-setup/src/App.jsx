import { HashRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Login from './components/Login';
import Navbar from './components/Navbar';
import Home from './components/Home';
import EmployeeList from './components/EmployeeList';
import AddEmployee from './components/AddEmployee';
import EditEmployee from './components/EditEmployee';
import Payroll from './components/Payroll';
import PeriodDetail from './components/PeriodDetail';
import Analytics from './components/Analytics';
import Footer from './components/Footer';
import PayslipView from './pages/PayslipView';

// 1. IMPORT THE NEW HISTORY COMPONENT
import EmployeeHistory from './components/EmployeeHistory';

// Auth Guard
const ProtectedRoute = ({ children }) => {
  const user = localStorage.getItem('user');
  if (!user) {
    return <Navigate to="/login" replace />;
  }
  return children;
};

function App() {
  return (
      <Router>
        <Routes>
          <Route path="/login" element={<Login />} />

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

                    {/* 2. ADD THE MISSING HISTORY ROUTE */}
                    <Route path="/employees/history/:code" element={<EmployeeHistory />} />

                    {/* --- PAYROLL ROUTES --- */}
                    <Route path="/payroll" element={<Payroll />} />
                    <Route path="/payroll/:id" element={<PeriodDetail />} />

                    {/* --- ANALYTICS ROUTE --- */}
                    <Route path="/analytics" element={<Analytics />} />

                    {/* --- REAL PAYSLIP ROUTE --- */}
                    <Route path="/payroll/payslip/:periodId/:runId" element={<PayslipView />}/>
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