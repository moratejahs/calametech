import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../home.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    context.read<SosReportsBloc>().add(SosReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<SosReportsBloc, SosReportsState>(
      builder: (context, state) {
        if (state is SosReportsLoading) {
          return const Center(
            child: CircularProgressIndicator(),
          );
        }

        if (state is SosReportsLoaded) {
          return ListView.builder(
            itemCount: state.sosReports.length,
            itemBuilder: (context, index) {
              final sosReport = state.sosReports[index];
              return ListTile(
                title: Text(sosReport.status),
                subtitle: Text(sosReport.date),
              );
            },
          );
        }

        if (state is SosReportsError) {
          return Center(
            child: Text(state.message),
          );
        }

        return const SizedBox();
      },
    );
  }
}
