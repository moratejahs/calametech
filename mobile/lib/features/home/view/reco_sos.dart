import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/features/home/home.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart' show GoRouter;

class RecoSos extends StatefulWidget {
  const RecoSos({super.key});

  @override
  State<RecoSos> createState() => _RecoSosState();
}

class _RecoSosState extends State<RecoSos> {
  @override
  void initState() {
    context.read<SosRecoReportsBloc>().add(SosRecoReportsFetched());
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Recommendations for you',
              style: TextStyle(
                fontSize: 20.0,
              ),
            ),
            TextButton(
              onPressed: () {
                GoRouter.of(context).go(RouteConstants.sosReports);
              },
              child: const Text('see more'),
            ),
          ],
        ),
        SizedBox(
          height: 200,
          child: BlocBuilder<SosRecoReportsBloc, SosRecoReportsState>(
            builder: (context, state) {
              if (state is SosRecoReportsLoading) {
                return const Center(
                  child: CircularProgressIndicator(),
                );
              }
              if (state is SosRecoReportsLoaded) {
                return SizedBox(
                  height: 250,
                  child: ListView.builder(
                    scrollDirection: Axis.horizontal,
                    itemCount: state.sosRecoReports.length,
                    itemBuilder: (context, index) {
                      return SosCard(
                        sosReport: state.sosRecoReports[index],
                      );
                    },
                  ),
                );
              }

              if (state is SosRecoReportsError) {
                return Center(
                  child: Text(state.message),
                );
              }

              return const SizedBox();
            },
          ),
        ),
      ],
    );
  }
}
