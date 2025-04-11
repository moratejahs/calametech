import 'package:calamitech/features/home/home.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

class FeaturedSos extends StatefulWidget {
  const FeaturedSos({super.key});

  @override
  State<FeaturedSos> createState() => _FeaturedSosState();
}

class _FeaturedSosState extends State<FeaturedSos> {
  @override
  void initState() {
    context.read<SosFeaturedReportsBloc>().add(SosFeaturedReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 250,
      child: BlocBuilder<SosFeaturedReportsBloc, SosFeaturedReportsState>(
        builder: (context, state) {
          if (state is SosFeaturedReportsLoading) {
            return const Center(
              child: CircularProgressIndicator(),
            );
          }

          if (state is SosFeaturedReportsLoaded) {
            return SizedBox(
              height: 300,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                itemCount: state.sosFeaturedReports.length,
                itemBuilder: (context, index) {
                  return SosCard(
                    sosReport: state.sosFeaturedReports[index],
                    fullWidth: true,
                  );
                },
              ),
            );
          }

          if (state is SosFeaturedReportsError) {
            return Center(
              child: Text(state.message),
            );
          }

          return const SizedBox();
        },
      ),
    );
  }
}
