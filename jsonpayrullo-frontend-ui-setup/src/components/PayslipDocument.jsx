import React from 'react';
import { Document, Page, Text, View, StyleSheet } from '@react-pdf/renderer';

const styles = StyleSheet.create({
    page: {
        fontFamily: 'Helvetica',
        backgroundColor: '#ffffff',
        fontSize: 10,
        color: '#1a1a1a',
    },
    // Header
    header: {
        backgroundColor: '#6d28d9',
        paddingHorizontal: 36,
        paddingVertical: 24,
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'flex-start',
    },
    headerRight: { flexDirection: 'column', alignItems: 'flex-end' },
    companyName: { fontSize: 20, fontFamily: 'Helvetica-Bold', color: '#ffffff', letterSpacing: 2 },
    companyTagline: { fontSize: 8, color: '#c4b5fd', letterSpacing: 2, marginTop: 3 },
    payslipLabel: { fontSize: 8, color: '#c4b5fd', letterSpacing: 2 },
    periodText: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#ffffff', marginTop: 4 },
    payDateText: { fontSize: 8, color: '#c4b5fd', marginTop: 2 },
    // Employee Section
    employeeSection: {
        paddingHorizontal: 36,
        paddingVertical: 20,
        flexDirection: 'row',
        justifyContent: 'space-between',
        borderBottomWidth: 1,
        borderBottomColor: '#e5e7eb',
    },
    employeeName: { fontSize: 16, fontFamily: 'Helvetica-Bold', color: '#111827' },
    employeeCode: { fontSize: 9, color: '#9ca3af', marginTop: 3 },
    labelSmall: { fontSize: 7, color: '#9ca3af', letterSpacing: 1.5, marginBottom: 3 },
    valueText: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#374151' },
    valueSubText: { fontSize: 9, color: '#6b7280', marginTop: 2 },
    alignRight: { alignItems: 'flex-end' },
    // Stats Row
    statsRow: {
        flexDirection: 'row',
        paddingHorizontal: 36,
        paddingVertical: 14,
        borderBottomWidth: 1,
        borderBottomColor: '#e5e7eb',
        gap: 12,
    },
    statBox: {
        flex: 1,
        backgroundColor: '#f9fafb',
        borderRadius: 6,
        padding: 10,
        borderWidth: 1,
        borderColor: '#e5e7eb',
    },
    statValue: { fontSize: 14, fontFamily: 'Helvetica-Bold', color: '#111827', marginTop: 4 },
    // Sections
    section: {
        paddingHorizontal: 36,
        paddingVertical: 16,
        borderBottomWidth: 1,
        borderBottomColor: '#e5e7eb',
    },
    sectionTitle: {
        fontSize: 7,
        fontFamily: 'Helvetica-Bold',
        color: '#9ca3af',
        letterSpacing: 2,
        marginBottom: 10,
    },
    lineRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingVertical: 4,
    },
    lineLabel: { fontSize: 10, color: '#6b7280' },
    lineValue: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#111827' },
    lineValueViolet: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#7c3aed' },
    lineValueRed: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#ef4444' },
    subtotalRow: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingVertical: 6,
        marginTop: 6,
        borderTopWidth: 1,
        borderTopColor: '#e5e7eb',
    },
    subtotalLabel: { fontSize: 10, fontFamily: 'Helvetica-Bold', color: '#111827' },
    // Net Pay
    netPaySection: {
        backgroundColor: '#f5f3ff',
        paddingHorizontal: 36,
        paddingVertical: 20,
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
    },
    netPayLabel: { fontSize: 12, fontFamily: 'Helvetica-Bold', color: '#5b21b6', letterSpacing: 1.5 },
    netPayValue: { fontSize: 26, fontFamily: 'Helvetica-Bold', color: '#6d28d9' },
    // Footer
    footer: {
        paddingHorizontal: 36,
        paddingVertical: 12,
        alignItems: 'center',
    },
    footerText: { fontSize: 8, color: '#d1d5db', textAlign: 'center' },
});

const fmt = (dateStr) => {
    if (!dateStr) return '---';
    return new Date(dateStr).toLocaleDateString('en-US', {
        month: 'long', day: 'numeric', year: 'numeric'
    });
};

const peso = (val) =>
    `PHP ${Number(val || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    })}`;

const PayslipDocument = ({ run, allowances, deductions, period }) => {
    const totalDeductions = deductions.reduce((s, d) => s + Number(d.amount || 0), 0);

    return (
        <Document title={`Payslip - ${run.full_name}`} author="Payrullo">
            <Page size="A4" style={styles.page}>

                {/* HEADER */}
                <View style={styles.header}>
                    <View>
                        <Text style={styles.companyName}>PAYRULLO</Text>
                        <Text style={styles.companyTagline}>HR & Payroll System</Text>
                    </View>
                    <View style={styles.headerRight}>
                        <Text style={styles.payslipLabel}>PAYSLIP</Text>
                        <Text style={styles.periodText}>
                            {fmt(period.period_start)} – {fmt(period.period_end)}
                        </Text>
                        <Text style={styles.payDateText}>Pay Date: {fmt(period.pay_date)}</Text>
                    </View>
                </View>

                {/* EMPLOYEE INFO */}
                <View style={styles.employeeSection}>
                    <View>
                        <Text style={styles.labelSmall}>EMPLOYEE</Text>
                        <Text style={styles.employeeName}>{run.full_name}</Text>
                        <Text style={styles.employeeCode}>{run.employee_code}</Text>
                    </View>
                    <View style={styles.alignRight}>
                        <Text style={styles.labelSmall}>POSITION</Text>
                        <Text style={styles.valueText}>{run.position}</Text>
                        <Text style={styles.valueSubText}>{run.department}</Text>
                    </View>
                </View>

                {/* ATTENDANCE STATS */}
                <View style={styles.statsRow}>
                    <View style={styles.statBox}>
                        <Text style={styles.labelSmall}>DAYS PRESENT</Text>
                        <Text style={styles.statValue}>{run.days_present ?? '—'}</Text>
                    </View>
                    <View style={styles.statBox}>
                        <Text style={styles.labelSmall}>DAYS LATE</Text>
                        <Text style={styles.statValue}>{run.days_late ?? '—'}</Text>
                    </View>
                    <View style={styles.statBox}>
                        <Text style={styles.labelSmall}>HOURS WORKED</Text>
                        <Text style={styles.statValue}>{Number(run.total_worked_hours || 0).toFixed(2)} hrs</Text>
                    </View>
                    <View style={styles.statBox}>
                        <Text style={styles.labelSmall}>SHIFT</Text>
                        <Text style={styles.statValue}>{run.shift_name || '—'}</Text>
                    </View>
                </View>

                {/* EARNINGS */}
                <View style={styles.section}>
                    <Text style={styles.sectionTitle}>EARNINGS</Text>
                    <View style={styles.lineRow}>
                        <Text style={styles.lineLabel}>Basic Pay</Text>
                        <Text style={styles.lineValue}>{peso(run.basic_pay)}</Text>
                    </View>
                    <View style={styles.lineRow}>
                        <Text style={styles.lineLabel}>Overtime Pay</Text>
                        <Text style={styles.lineValue}>{peso(run.overtime_pay)}</Text>
                    </View>
                    {allowances.map((al, i) => (
                        <View key={i} style={styles.lineRow}>
                            <Text style={styles.lineLabel}>{al.name}</Text>
                            <Text style={styles.lineValueViolet}>{peso(al.amount)}</Text>
                        </View>
                    ))}
                    <View style={styles.subtotalRow}>
                        <Text style={styles.subtotalLabel}>Gross Pay</Text>
                        <Text style={styles.subtotalLabel}>{peso(run.gross_pay)}</Text>
                    </View>
                </View>

                {/* DEDUCTIONS */}
                <View style={styles.section}>
                    <Text style={styles.sectionTitle}>DEDUCTIONS</Text>
                    {deductions.length === 0 && (
                        <Text style={{ fontSize: 9, color: '#9ca3af' }}>No deductions.</Text>
                    )}
                    {deductions.map((ded, i) => (
                        <View key={i} style={styles.lineRow}>
                            <Text style={styles.lineLabel}>{ded.name}</Text>
                            <Text style={styles.lineValueRed}>- {peso(ded.amount)}</Text>
                        </View>
                    ))}
                    {deductions.length > 0 && (
                        <View style={styles.subtotalRow}>
                            <Text style={[styles.subtotalLabel, { color: '#ef4444' }]}>Total Deductions</Text>
                            <Text style={[styles.subtotalLabel, { color: '#ef4444' }]}>- {peso(totalDeductions)}</Text>
                        </View>
                    )}
                </View>

                {/* NET PAY */}
                <View style={styles.netPaySection}>
                    <Text style={styles.netPayLabel}>NET PAY</Text>
                    <Text style={styles.netPayValue}>{peso(run.net_pay)}</Text>
                </View>

                {/* FOOTER */}
                <View style={styles.footer}>
                    <Text style={styles.footerText}>
                        This is a system-generated payslip and does not require a signature. • Payrullo HR System
                    </Text>
                </View>

            </Page>
        </Document>
    );
};

export default PayslipDocument;