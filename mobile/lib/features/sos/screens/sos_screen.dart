import 'package:calamitech/constants/route_constants.dart';
import 'package:calamitech/core/location/cubit/location_cubit.dart';
import 'package:calamitech/features/sos/bloc/sos_bloc.dart';
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:go_router/go_router.dart';

import '../../../config/theme/app_theme.dart';

class SOSScreen extends StatefulWidget {
  const SOSScreen({super.key});

  @override
  State<SOSScreen> createState() => _SOSScreenState();
}

class _SOSScreenState extends State<SOSScreen> {
  @override
  void initState() {
    super.initState();
    context.read<LocationCubit>().startLocationUpdates();
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<SosBloc, SosState>(
      listener: (context, state) {
        if (state is SosSuccess) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(
              content: Text('SOS sent to CDRRMO.'),
              backgroundColor: Colors.green,
              duration: Duration(seconds: 5),
              behavior: SnackBarBehavior.floating,
            ),
          );

          context.go(RouteConstants.report);
        }

        if (state is SosFailure) {
          debugPrint('Failed to send SOS: ${state.message}');

          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(
              content: Text('${state.message}'),
              backgroundColor: Colors.red,
              behavior: SnackBarBehavior.floating,
            ),
          );
        }
      },
      child: Center(
        child: Column(
          children: [
            const Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                SizedBox(height: 400, child: SOSButton()),
              ],
            ),
            const SizedBox(height: 20),
            BlocBuilder<LocationCubit, LocationCubitState>(
              builder: (context, state) {
                if (state.error != null) {
                  return const SizedBox();
                }

                if (state.latitude < 1) {
                  return Row(
                    spacing: 10,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Text(
                        'Identifying Location...',
                        style: TextStyle(
                          fontSize: 18,
                        ),
                      ),
                      SizedBox(
                        width: 20,
                        height: 20,
                        child: CircularProgressIndicator(
                          strokeWidth: 2.0,
                          valueColor: AlwaysStoppedAnimation(AppTheme.primaryColor),
                        ),
                      )
                    ],
                  );
                }

                return Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Location Detected',
                      style: TextStyle(
                        fontSize: 26,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      'Latitude: ${state.latitude}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w400,
                      ),
                    ),
                    Text(
                      'Longitude: ${state.longitude}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.w400,
                      ),
                    ),
                  ],
                );
              },
            ),
          ],
        ),
      ),
    );
  }
}

class SOSButton extends StatefulWidget {
  const SOSButton({super.key});

  @override
  _SOSButtonState createState() => _SOSButtonState();
}

class _SOSButtonState extends State<SOSButton> with SingleTickerProviderStateMixin {
  double _scale = 1.0;
  late AnimationController _controller;
  late Animation<double> _scaleAnimation;
  late Animation<double> _opacityAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      vsync: this,
      duration: const Duration(seconds: 3), // Slower animation for smoother effect
    )..repeat(reverse: false);

    _scaleAnimation = Tween<double>(begin: 1.0, end: 2.0).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeOutQuad),
    );

    _opacityAnimation = Tween<double>(begin: 0.4, end: 0.0).animate(
      CurvedAnimation(parent: _controller, curve: Curves.easeOut),
    );
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  void _onTapDown(TapDownDetails details) {
    setState(() {
      _scale = 0.9;
    });
  }

  void _onTapUp(TapUpDetails details) {
    setState(() {
      _scale = 1.0;
    });
    _sendSOS();
  }

  void _onTapCancel() {
    setState(() {
      _scale = 1.0;
    });
  }

  void _sendSOS() async {
    // final activeSOS = await _checkActiveSOS();
    //
    // if (activeSOS != null) {
    //   debugPrint('User has Active SOS: $activeSOS');
    //   return;
    // }

    if (context.read<LocationCubit>().state.latitude <= 0) {
      debugPrint('Location not available');

      return;
    }

    context.read<SosBloc>().add(SOSRequested(
          lat: context.read<LocationCubit>().state.latitude,
          long: context.read<LocationCubit>().state.longitude,
        ));
  }

  // Future<SOS?> _checkActiveSOS() async {
  //   final storage = SecureStorageService();
  //   final activeSOS = await storage.readValue('sos');
  //
  //   if (activeSOS != null) {
  //     final sos = SOS.fromJson(activeSOS);
  //
  //     debugPrint('Active SOS exists: $sos');
  //
  //     return sos;
  //   }
  //
  //   return null;
  // }

  @override
  Widget build(BuildContext context) {
    return Center(
      child: GestureDetector(
        onTapDown: _onTapDown,
        onTapUp: _onTapUp,
        onTapCancel: _onTapCancel,
        child: AnimatedScale(
          scale: _scale,
          duration: const Duration(milliseconds: 100),
          child: Stack(
            alignment: Alignment.center,
            children: [
              AnimatedBuilder(
                animation: _controller,
                builder: (context, child) {
                  return Container(
                    width: 150 * _scaleAnimation.value,
                    height: 150 * _scaleAnimation.value,
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: Colors.red.withValues(alpha: _opacityAnimation.value),
                    ),
                  );
                },
              ),
              Container(
                width: 180,
                height: 180,
                decoration: BoxDecoration(
                  color: Colors.red,
                  shape: BoxShape.circle,
                  boxShadow: [
                    BoxShadow(
                      color: Colors.red.withValues(alpha: 0.6),
                      spreadRadius: 4,
                      blurRadius: 10,
                    ),
                  ],
                ),
                child: Center(
                  child: BlocBuilder<SosBloc, SosState>(
                    builder: (context, state) {
                      if (state is SosLoading) {
                        return const CircularProgressIndicator(
                          valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                        );
                      }

                      return const Column(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            'SOS',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 40,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          Text(
                            'Tap to send',
                            style: TextStyle(
                              color: Colors.white,
                              fontSize: 16,
                            ),
                          ),
                        ],
                      );
                    },
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
