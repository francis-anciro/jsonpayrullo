import React, { useState, useEffect } from 'react';
import { PieChart, Pie, Cell, BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer, Legend } from 'recharts';
import { BarChart3, Loader2, Users, Building2, Briefcase } from 'lucide-react';

const Analytics = () => {
  const [isMounted, setIsMounted] = useState(false);
  const [isLoading, setIsLoading] = useState(true);
  
  // Data States
  const [statusData, setStatusData] = useState([]);
  const [deptData, setDeptData] = useState([]);
  
  // UPGRADED: Backend-ready KPIs based on your SQL schema
  const [kpiData, setKpiData] = useState({ total: 0, activeDepts: 0, totalRoles: 0 });

  // Colors for the Pie Chart slices
  const PIE_COLORS = ['#22c55e', '#ef4444', '#f59e0b'];

  useEffect(() => {
    setIsMounted(true);

    const fetchAnalytics = async () => {
      try {
        // Simulated network delay
        await new Promise(resolve => setTimeout(resolve, 800)); 

        // Simulated PHP Responses
        setStatusData([
          { name: 'Active', value: 85 },
          { name: 'Resigned', value: 12 },
          { name: 'On Leave', value: 3 }
        ]);

        setDeptData([
          { name: 'IT', employees: 35 },
          { name: 'HR', employees: 8 },
          { name: 'Finance', employees: 12 },
          { name: 'Marketing', employees: 15 },
          { name: 'Operations', employees: 30 },
        ]);

        // UPGRADED: Mock data matching backend schema possibilities
        setKpiData({
          total: 100,          // COUNT(*) FROM employees
          activeDepts: 5,      // COUNT(*) FROM departments
          totalRoles: 24       // COUNT(*) FROM positions
        });

      } catch (error) {
        console.error("Failed to fetch analytics:", error);
      } finally {
        setIsLoading(false);
      }
    };

    fetchAnalytics();
  }, []);

  // Custom Tooltip for Dark Mode UI
  const CustomTooltip = ({ active, payload, label }) => {
    if (active && payload && payload.length) {
      return (
        <div className="bg-[#0a0a0a]/90 backdrop-blur-md border border-zinc-800 p-4 rounded-xl shadow-[0_10px_30px_rgba(0,0,0,0.8)] flex flex-col gap-1 z-50">
          <p className="text-white font-black tracking-wider uppercase text-xs mb-1">{label || payload[0].name}</p>
          <p className="text-amber-400 font-bold text-lg drop-shadow-md">
            {payload[0].value} <span className="text-zinc-500 text-[10px] font-medium uppercase tracking-widest">Employees</span>
          </p>
        </div>
      );
    }
    return null;
  };

  return (
    <div className="relative flex flex-col items-center p-6 md:p-10 min-h-[calc(100vh-4rem)] overflow-hidden">
      
      {/* --- BACKGROUND LAYER --- */}
      <div 
        className={`fixed inset-0 z-0 pointer-events-none transition-opacity duration-1000 opacity-50`}
        style={{ backgroundImage: 'radial-gradient(#666666 1px, transparent 1px)', backgroundSize: '24px 24px' }}
      ></div>
      
      {/* Ambient Lights */}
      <div className={`fixed top-0 left-0 h-full w-1/4 bg-gradient-to-r from-amber-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-all duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>
      <div className={`fixed top-0 right-0 h-full w-1/4 bg-gradient-to-l from-amber-500/20 to-transparent blur-3xl pointer-events-none z-0 transition-all duration-1000 ${isMounted ? 'opacity-100' : 'opacity-0'}`}></div>

      <div className={`relative z-10 w-full max-w-6xl flex flex-col gap-8 transition-all duration-700 ease-out transform ${isMounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'}`}>
        
        {/* HEADER */}
        <div className="flex flex-col md:flex-row justify-between items-start md:items-end w-full gap-4">
          <div className="flex flex-col">
            <h1 className="text-2xl md:text-3xl font-black text-white tracking-wide uppercase drop-shadow-lg flex items-center gap-3">
              <BarChart3 className="text-amber-500" size={32} /> Analytics
            </h1>
            <p className="text-amber-400/80 font-bold text-xs md:text-sm tracking-widest uppercase mt-1">
              Company Overview & Demographics
            </p>
          </div>
        </div>

        {/* --- KPI SUMMARY CARDS --- */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 w-full">
          {[
            { label: 'Total Workforce', value: kpiData.total, icon: Users, color: 'text-amber-500', bg: 'bg-amber-500/10', border: 'border-amber-500/20' },
            { label: 'Active Departments', value: kpiData.activeDepts, icon: Building2, color: 'text-blue-500', bg: 'bg-blue-500/10', border: 'border-blue-500/20' },
            { label: 'Total Positions', value: kpiData.totalRoles, icon: Briefcase, color: 'text-emerald-500', bg: 'bg-emerald-500/10', border: 'border-emerald-500/20' }
          ].map((kpi, idx) => (
            <div key={idx} className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-xl rounded-[2rem] p-6 flex items-center gap-6 relative overflow-hidden group hover:-translate-y-1 hover:border-zinc-700 transition-all duration-300">
              <div className={`w-14 h-14 rounded-2xl flex items-center justify-center border ${kpi.bg} ${kpi.border} ${kpi.color} shadow-inner`}>
                <kpi.icon size={26} />
              </div>
              <div className="flex flex-col z-10">
                <span className="text-[10px] font-bold text-zinc-500 uppercase tracking-widest mb-1">{kpi.label}</span>
                {isLoading ? (
                  <Loader2 className="animate-spin text-zinc-600 mt-1" size={20} />
                ) : (
                  <span className="text-3xl font-black text-white tracking-wide">{kpi.value}</span>
                )}
              </div>
            </div>
          ))}
        </div>

        <div className="bg-gradient-to-b from-[#121212]/90 to-[#0a0a0a]/90 backdrop-blur-xl border border-zinc-800/80 shadow-[0_16px_40px_0_rgba(0,0,0,0.5),inset_0_1px_1px_rgba(255,255,255,0.05)] rounded-[2.5rem] p-6 md:p-8 flex flex-col gap-6 relative overflow-hidden">
          
          <div className="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-amber-500/20 to-transparent"></div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
            
            {/* Chart 1: Status Distribution (Donut Chart) */}
            <div className="flex flex-col min-h-[400px]">
              
              <div className="flex items-center gap-3 bg-zinc-900/40 border border-zinc-800/80 shadow-inner rounded-2xl px-5 py-4 mb-6 w-full">
                <Users className="text-amber-500" size={18} />
                <h2 className="text-xs font-black text-zinc-300 uppercase tracking-[0.15em]">
                  Employment Status
                </h2>
              </div>

              {isLoading ? (
                <div className="flex flex-col items-center justify-center flex-1 gap-4">
                  <Loader2 className="text-amber-500 animate-spin" size={40} />
                </div>
              ) : (
                <div className="flex-1 w-full h-full min-h-[300px]">
                  <ResponsiveContainer width="100%" height="100%">
                    <PieChart>
                      <Pie
                        data={statusData}
                        cx="50%"
                        cy="50%"
                        innerRadius={90} 
                        outerRadius={130}
                        paddingAngle={4}
                        dataKey="value"
                        stroke="none"
                      >
                        {statusData.map((entry, index) => (
                          <Cell key={`cell-${index}`} fill={PIE_COLORS[index % PIE_COLORS.length]} style={{ filter: `drop-shadow(0px 4px 8px rgba(0,0,0,0.5))` }} />
                        ))}
                      </Pie>
                      <Tooltip content={<CustomTooltip />} cursor={{fill: 'transparent'}} />
                      <Legend 
                        verticalAlign="bottom" 
                        height={36} 
                        iconType="circle"
                        wrapperStyle={{ fontSize: '11px', fontWeight: 'bold', color: '#a1a1aa', textTransform: 'uppercase', letterSpacing: '0.1em' }}
                      />
                    </PieChart>
                  </ResponsiveContainer>
                </div>
              )}
            </div>

            {/* Chart 2: Headcount by Department (Bar Chart) */}
            <div className="flex flex-col min-h-[400px]">
              
              <div className="flex items-center gap-3 bg-zinc-900/40 border border-zinc-800/80 shadow-inner rounded-2xl px-5 py-4 mb-6 w-full">
                <Building2 className="text-amber-500" size={18} />
                <h2 className="text-xs font-black text-zinc-300 uppercase tracking-[0.15em]">
                  Headcount by Department
                </h2>
              </div>

              {isLoading ? (
                <div className="flex flex-col items-center justify-center flex-1 gap-4">
                  <Loader2 className="text-amber-500 animate-spin" size={40} />
                </div>
              ) : (
                <div className="flex-1 w-full h-full min-h-[300px]">
                  <ResponsiveContainer width="100%" height="100%">
                    <BarChart data={deptData} margin={{ top: 10, right: 10, left: -20, bottom: 0 }}>

                      <defs>
                        <linearGradient id="colorAmber" x1="0" y1="0" x2="0" y2="1">
                          <stop offset="0%" stopColor="#fbbf24" stopOpacity={1}/>
                          <stop offset="100%" stopColor="#d97706" stopOpacity={1}/>
                        </linearGradient>
                      </defs>

                      <XAxis 
                        dataKey="name" 
                        stroke="#52525b" 
                        tick={{ fill: '#a1a1aa', fontSize: 10, fontWeight: 'bold', textTransform: 'uppercase' }} 
                        tickLine={false}
                        axisLine={false}
                        dy={10}
                      />
                      <YAxis 
                        stroke="#52525b" 
                        tick={{ fill: '#52525b', fontSize: 12, fontWeight: 'bold' }} 
                        tickLine={false}
                        axisLine={false}
                      />
                      <Tooltip content={<CustomTooltip />} cursor={{fill: '#27272a', opacity: 0.4, radius: 8}} />
                      <Bar 
                        dataKey="employees" 
                        fill="url(#colorAmber)" 
                        radius={[8, 8, 8, 8]} 
                        barSize={45}
                      />
                    </BarChart>
                  </ResponsiveContainer>
                </div>
              )}
            </div>

          </div>
        </div>
      </div>
    </div>
  );
};

export default Analytics;