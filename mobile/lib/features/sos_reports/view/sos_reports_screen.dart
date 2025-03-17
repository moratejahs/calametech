import 'package:calamitech/config/theme/app_theme.dart';
import 'package:calamitech/constants/route_constants.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:calamitech/features/sos_reports/sos_reports.dart';
import 'package:go_router/go_router.dart';

class SosReportsScreen extends StatefulWidget {
  const SosReportsScreen({super.key});

  @override
  State<SosReportsScreen> createState() => _SosReportsnState();
}

class _SosReportsnState extends State<SosReportsScreen> {
  @override
  void initState() {
    context.read<SosReportsBloc>().add(SosReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        backgroundColor: AppTheme.primaryColor,
        leading: IconButton(
          icon: const Icon(Icons.arrow_back, color: Colors.white),
          onPressed: () => context.go(RouteConstants.home),
        ),
        title: const Text('SOS Reports', style: TextStyle(color: Colors.white)),
      ),
      body: SafeArea(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: BlocBuilder<SosReportsBloc, SosReportsState>(
            builder: (context, state) {
              if (state is SosReportsLoading) {
                return const Center(
                  child: CircularProgressIndicator(),
                );
              }

              if (state is SosReportsLoaded) {
                return state.sosReports.isEmpty
                    ? const Center(
                        child: Text('No SOS reports available'),
                      )
                    : Expanded(
                        child: ListView.builder(
                          scrollDirection: Axis.vertical,
                          itemCount: state.sosReports.length,
                          itemBuilder: (context, index) {
                            return Container(
                              margin: const EdgeInsets.only(bottom: 12),
                              width: double.infinity,
                              child: SosCard(
                                sosReport: state.sosReports[index],
                                fullWidth: true,
                              ),
                            );
                          },
                        ),
                      );
              }

              if (state is SosReportsError) {
                return Center(
                  child: Text(state.message),
                );
              }

              return const SizedBox();
            },
          ),
        ),
      ),
    );
  }
}
